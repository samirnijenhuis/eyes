<?php

namespace App\Browsers;

use App\Browsers\Traits\Capture;
use App\Contracts\Browser as BrowserContract;
use App\Browsers\Dusk\AbstractDusk as Dusk;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

final class Safari extends Dusk implements BrowserContract {

    use Capture;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public function prepare()
    {
        static::startPhantomDriver();

    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            'http://localhost:8910/wd/hub', DesiredCapabilities::safari()
        );

    }

}