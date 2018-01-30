<?php

namespace App\Repositories;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class PageRepository implements Arrayable, Jsonable
{
    protected $page;

    public function __construct($page)
    {
        $this->page = collect($page);
    }

    /**
     * Generate a JS script that hides/ignores the given component.
     *
     * @return array
     */
    public function ignore()
    {
        return collect((array) $this->page->get('ignore', []))->map(function($component){
            return "var elements = document.querySelectorAll('{$component}'); for(var i = 0; i < elements.length; i++) {elements[i].style.display = 'none';}";
        })->all();
    }

    /**
     * Loads scripts.
     *
     * @return array
     */
    public function scripts()
    {
        return collect((array) $this->page->get('scripts', []))->map(function($script){
            // If it's a local file, search from the base dir.
            if(! str_contains($script, '://') ) {
                $script = base_path($script);
            }
            return file_get_contents($script);
        })->all();
    }

    /**
     * Grabs the given width.
     *
     * @return int
     */
    public function width($size)
    {
        list($width, $height) = explode('x', $size, 2);
        return (int) $width;
    }

    /**
     * Grabs the given height.
     *
     * @return int
     */
    public function height()
    {
        list($width, $height) = explode('x', $size, 2);
        return (int) $height;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->page->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return $this->page->toJson($options);
    }


    /**
     * Grabs the $page key first by kebabcase, then by given case.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->page->get(
            kebab_case($key),
            $this->page->get($key)
        );
    }

}