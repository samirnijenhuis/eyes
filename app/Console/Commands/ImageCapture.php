<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Screen\Capture;

class ImageCapture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:capture {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture images';

    protected $settings = [];
    protected $name = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = $this->parseEyesFile();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = $this->argument('name');

        $this->bar = $this->output->createProgressBar(
            count($this->settings['sizes']) * count($this->settings['pages'])
        );

        foreach($this->settings['sizes'] as $size) {
            $this->handleSize($size);
        }

        $this->bar->finish();
    }

    private function handleSize($size) {
        foreach($this->settings['pages'] as $page) {
            $this->capturePage($page, $size);
            $this->bar->advance();
        }
    }

    protected function capturePage($page, $size) {
        $screenCapture = new Capture();

        $screenCapture->setUrl($page['url']);
        $screenCapture->setBinPath('/usr/local/bin/');

        list($width, $height) = explode('x', $size, 2);
        $screenCapture->setWidth($width);
        $screenCapture->setHeight($height);
        $screenCapture->setImageType(\Screen\Image\Types\Png::FORMAT);
        $screenCapture->setDelay($page['wait-for-delay']);

         $filePath = "app/.eyes/" . $this->name . '/' . $size . '/' . $page['name'] . '.' . $screenCapture->getImageType()->getFormat();

        $screenCapture->save(storage_path( $filePath ));
    }

    private function parseEyesFile()
    {
        $json = file_get_contents(base_path('eyes.json'));
        return json_decode($json, true);
    }
}
