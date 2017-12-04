<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager as Storage;
use Imagick;

class ImageCompare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:compare {before} {after} {--transparent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare images';
    /**
     * @var \Illuminate\Filesystem\FilesystemManager
     */
    private $storage;


    private $transparent = false;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     */
    public function __construct(Storage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baseline = $this->argument('before');
        $current = $this->argument('after');


        $files = $this->storage->allFiles( '.eyes/' . $baseline . '/' );


        $bar = $this->output->createProgressBar(count($files));
        $output = [];
        foreach($files as $file) {
            if(basename($file) == '.DS_Store') {
                continue;
            }
            $output[] = $this->compare($file, $baseline, $current);
            $bar->advance();
        }

        $this->outputHTML($output);

        $bar->finish();
    }

    public function outputHTML($output)
    {
        $json = collect($output)->groupBy('filename')->transform(function($value, $key){
            return
                ["title" => $key, 'resolutions' => collect($value)->keyBy('dimension'), 'selected' => $value->first()['dimension']];
        })->values()->toJson();

        $view = view('difference')->with('difference' , $json);

//        $diff_file = str_replace(".eyes/{$this->argument('baseline')}", "" ,$current_file);

        $this->storage->put(".eyes/diff_{$this->argument('before')}_{$this->argument('after')}/output.html", $view);


    }

    public function compare($base_file, $baseline, $current)
    {
        $image1 = new Imagick();

        if($this->option('transparent')) {
            // Set Lowlight (resulting background) to transparent, not "original image with a bit of opacity"
            $image1->setOption('lowlight-color','transparent');

            // Switch the default compose operator to Src, like in example
            $image1->setOption('compose', 'Src');
        }
        $image1->setOption('fuzz', '100');

        $image1->readImage(storage_path( "app/" .$base_file));

        // Load the corresponding images.
        $image2 = new Imagick();
        $current_file = str_replace(".eyes/{$baseline}", ".eyes/{$current}", $base_file);
        $image2->readImage(storage_path("app/" .$current_file));

        // Compare image 1 with image 2.
        $result = $image1->compareImages($image2, Imagick::METRIC_MEANSQUAREERROR);
        $result[0]->setImageFormat("png");

        $diff_file = str_replace(".eyes/{$current}", ".eyes/diff_{$baseline}_{$current}" ,$current_file);

        // Write file.
        $this->storage->put($diff_file, $result[0]);


        return [
            "filename" => pathinfo($base_file, PATHINFO_FILENAME),
            "dimension" => basename(dirname($base_file)),
            "before" => storage_path("app/" . $base_file),
            "after" => storage_path("app/" . $current_file),
            "difference" => storage_path("app/" . $diff_file),
            "percentage" => $result[1]
        ];
    }

}
