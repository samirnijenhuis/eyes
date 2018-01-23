<?php

namespace App\Console\Commands;

use App\Contracts\Browser;
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
     * Default settings for a page.
     */
    const PAGES_DEFAULTS = [
        "name" => null,
        "url" => null,
        "wait-for-delay" => 0,
        "ignore" => [],
        "scripts" => [],
    ];

    /**
     * @var array
     */
    protected $pages = [];

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * The (group) name of the current run.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Progress bar.
     *
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    private $bar;

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

        $this->browser = $browser;
        $this->sizes   = $this->parseEyesFile('sizes');
        $this->setPages( $this->parseEyesFile('pages') );
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
            count($this->sizes) * count($this->pages)
        );

        foreach($this->sizes as $size) {
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
        foreach($this->pages as $page) {
            $this->browser->capture($this->name, $page, $size);
            $this->bar->advance();
        }
    }

    /**
     * Populate the pages array (merge each page with the defaults).
     * @param array $pages
     *
     * @return void
     */
    public function setPages($pages){
        $this->pages = collect($pages)->map(function($page){
            return array_merge(self::PAGES_DEFAULTS, array_filter($page));
        })->toArray();
    }

    /**
     * Takes the Eyes file and transforms it to an array.
     *
     * @return mixed
     * @throws \Exception
     */
    private function parseEyesFile($key = null)
    {
        $file = base_path('eyes.json');
        if( ! file_exists($file)) {
            throw new \Exception("Eyes file doesn't exist ({$file})");
        }

        $json = file_get_contents($file);
        $settings = json_decode($json, true);

        return data_get($settings, $key);
    }
}
