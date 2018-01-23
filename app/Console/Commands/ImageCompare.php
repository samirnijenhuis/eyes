<?php

namespace App\Console\Commands;

use App\Events\ImagesCompared;
use App\Services\ImageService;
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
     * @var \App\Services\ImageService
     */
    private $imageService;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     * @param \App\Services\ImageService               $imageService
     */
    public function __construct(Storage $storage, ImageService $imageService)
    {
        parent::__construct();
        $this->storage = $storage;
        $this->imageService = $imageService;
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
            $output[] = $this->imageService->compare($file, $baseline, $current);
            $bar->advance();
        }

        event(new ImagesCompared($baseline, $current, $output));

        $bar->finish();
    }


}
