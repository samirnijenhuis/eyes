<?php

namespace App\Services;

use App\Jobs\CapturePage;

class PageService {

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
    public $pages = [];

    /**
     * @var array
     */
    public $sizes = [];

    /**
     * The (group) name of the current run.
     *
     * @var string
     */
    public $name = '';


    public function __construct()
    {
        $this->sizes   = $this->parseSettings('sizes');
        $this->pages   = $this->transformPages(
            $this->parseSettings('pages')
        );
    }

    /**
     * @param $name
     * @param $page
     * @param $size
     */
    public function capture($page, $size)
    {
        dispatch_now(new CapturePage($this->name, $page, $size));
    }

    /**
     * Populate the pages array (merge each page with the defaults).
     * @param array $pages
     *
     * @return array
     */
    private function transformPages($pages){
        return collect($pages)->map(function($page){
            return array_merge(self::PAGES_DEFAULTS, array_filter($page));
        })->toArray();
    }


    /**
     * Takes the Eyes file and transforms it to an array.
     *
     * @param null  $key
     * @param array $default
     *
     * @return mixed
     * @throws \Exception
     */
    private function parseSettings($key = null, $default = [])
    {
        $file = base_path('eyes.json');
        if( ! file_exists($file)) {
            throw new \Exception("Eyes file doesn't exist ({$file})");
        }

        $json = file_get_contents($file);
        $settings = json_decode($json, true);

        return data_get($settings, $key, $default);
    }

    /**
     * Count the amount of pages.
     *
     * @return int
     */
    public function countPages()
    {
        return count($this->sizes) * count($this->pages);
    }

}