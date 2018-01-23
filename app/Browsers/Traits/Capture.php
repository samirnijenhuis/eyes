<?php

namespace App\Browsers\Traits;

use Laravel\Dusk\Browser;

trait Capture {

    /**
     * Takes a screenshot from a Dusk-ran browser.
     *
     * @param $group
     * @param $page
     * @param $size
     *
     * @return void
     */
    public function capture($group, $page, $size)
    {
        $this->group = $group;
        $this->name = $page['name'];

        $this->setUp();

        $page['ignore'] = collect((array) $page['ignore'])->map(function($component){
            return "var elements = document.querySelectorAll('{$component}'); for(var i = 0; i < elements.length; i++) {elements[i].style.display = 'none';}";
        })->all();


        $page['scripts'] = collect((array) $page['scripts'])->map(function($script){
            // If it's a local file, search from the base dir.
            if(! starts_with($script, ['http', 'https'])) {
                $script = base_path($script);
            }

            return file_get_contents($script);
        })->all();

        $this->browse(function (Browser $browser)  use($size, $page) {
            list($width, $height) = explode('x', $size, 2);

            $browser->visit($page['url'])
                ->resize(intval($width), intval($height))
                ->pause($page['wait-for-delay']);

            $browser->script($page['ignore']);
            $browser->script($page['scripts']);

            if( ! file_exists($browser::$storeScreenshotsAt . '/' . $size)) {
                mkdir($browser::$storeScreenshotsAt . '/' . $size);
            }

            $browser->screenshot($size . '/' . $this->name);
        });

        session()->flush();

        $this->shutdown();
    }
}