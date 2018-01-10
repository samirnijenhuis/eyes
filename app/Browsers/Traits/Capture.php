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

        $this->browse(function (Browser $browser)  use($size, $page) {
            list($width, $height) = explode('x', $size, 2);

            $browser->visit($page['url'])
                ->resize(intval($width), intval($height))
                ->pause($page['wait-for-delay'])
                ->screenshot($this->name);
        });

        session()->flush();

        $this->shutdown();
    }
}