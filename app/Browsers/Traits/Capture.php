<?php

namespace App\Browsers\Traits;

use App\Repositories\PageRepository;
use Laravel\Dusk\Browser;

trait Capture {

    /**
     * Takes a screenshot from a Dusk-ran browser.
     *
     * @param                                  $group
     * @param \App\Repositories\PageRepository $page
     * @param                                  $size
     *
     * @return void
     */
    public function capture($group, PageRepository $page, $size)
    {
        $this->group = $group;
        $this->name = $page->name;

        $this->setUp();



        $this->browse(function (Browser $browser)  use($size, $page) {
            $browser->visit($page->url)
                ->resize($page->width($size), $page->height($size));

            $browser->script($page->scripts());

            $browser->pause($page->waitForDelay);

            $browser->script($page->ignore());


            if( ! file_exists($browser::$storeScreenshotsAt . '/' . $size)) {
                mkdir($browser::$storeScreenshotsAt . '/' . $size);
            }

            $browser->screenshot($size . '/' . "{get_class($this)}-{$this->name}");
        });

        session()->flush();

        $this->shutdown();
    }
}