<?php

namespace App\Services;

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


    public function __construct()
    {
        $this->sizes   = $this->parseSettings('sizes');
        $this->pages   = $this->transformPages(
            $this->parseSettings('pages')
        );
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
    public function getPages()
    {
        return $this->pages;
    }

    public function getSizes()
    {
        return $this->sizes;
    }

    public function setName($name)
    {
        $this->name = $name;
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
     * @param null $key
     *
     * @return mixed
     * @throws \Exception
     */
    private function parseSettings($key = null)
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