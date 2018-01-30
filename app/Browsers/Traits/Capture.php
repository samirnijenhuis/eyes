<?php

namespace App\Browsers\Traits;

use App\Repositories\PageRepository;
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


        $page = new PageRepository($page);

        $this->browse(function (Browser $browser)  use($size, $page) {
            $browser->visit($page->url)
                ->resize($page->width(), $page->height());

            $browser->script($page->scripts());

            $browser->pause($page->waitForDelay);

            $browser->script($page->ignore());


            if( ! file_exists($browser::$storeScreenshotsAt . '/' . $size)) {
                mkdir($browser::$storeScreenshotsAt . '/' . $size);
            }

            $browser->screenshot($size . '/' . $this->name);
        });

        session()->flush();

        $this->shutdown();
    }
}