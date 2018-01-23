<?php

namespace App\Console\Commands;

use App\Contracts\Browser;
use App\Jobs\CapturePage;
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
    protected $pagesService;


    /**
     * Progress bar.
     *
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    private $bar;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->pagesService = new PageService( );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pagesService->setName(
            $this->argument('name')
        );

        $this->bar = $this->output->createProgressBar(
            $this->pagesService->countPages()
        );

        foreach($this->pagesService->getSizes() as $size) {
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
        foreach($this->pagesService->getPages() as $page) {
            dispatch_now(new CapturePage($this->name, $page, $size));
            $this->bar->advance();
        }
    }



}
