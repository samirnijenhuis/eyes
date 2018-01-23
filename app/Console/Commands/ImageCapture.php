<?php

namespace App\Console\Commands;

use App\Services\PageService;
use Illuminate\Console\Command;

class ImageCapture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:capture 
                            {name : Identifier to group your screenshots}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture images';

    /**
     * Progress bar.
     *
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    private $bar;

    /**
     * @var \App\Services\PageService
     */
    private $pagesService;

    /**
     * ImageCapture constructor.
     *
     * @param \App\Services\PageService $pagesService
     */
    public function __construct(PageService $pagesService)
    {
        parent::__construct();

        $this->pagesService = $pagesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pagesService->name = $this->argument('name');

        $this->bar = $this->output->createProgressBar(
            $this->pagesService->countPages()
        );

        foreach($this->pagesService->sizes as $size) {
            $this->captureForSize($size);
        }

        $this->bar->finish();
    }

    /**
     * Captures a browser image for each page in the given size/dimension.
     * @param $size string (e.g. "1920x1080")
     *
     * @return void
     */
    private function captureForSize($size) {
        foreach($this->pagesService->pages as $page) {
            $this->pagesService->capture($page, $size);
            $this->bar->advance();
        }
    }

}
