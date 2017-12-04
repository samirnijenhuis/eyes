<?php

namespace App\Console\Commands;

use App\Contracts\Browser;
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
     * @var \App\Contracts\Browser
     */
    private $browser;

    /**
     * Create a new command instance.
     *
     * @param \App\Contracts\Browser $browser
     */
    public function __construct(Browser $browser)
    {
        parent::__construct();

        $this->settings = $this->parseEyesFile();
        $this->browser = $browser;
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
            $this->browser->capture($this->name, $page, $size);
            $this->bar->advance();
        }
    }


    private function parseEyesFile()
    {
        $json = file_get_contents(base_path('eyes.json'));
        return json_decode($json, true);
    }
}
