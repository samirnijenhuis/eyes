<?php

namespace App\Browsers;

use App\Browsers\Traits\Capture;
use App\Contracts\Browser as BrowserContract;
use App\Browsers\Dusk\AbstractDusk as Dusk;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

final class Chrome extends Dusk implements BrowserContract {

    use Capture;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {

        $options = (new ChromeOptions())->addArguments([
//            '--disable-gpu',
            '--headless',
        ]);

        $capabilities = DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        );


        $port =  config('eyes.drivers.chrome.port');
        return RemoteWebDriver::create(
            "http://localhost:{$port}", $capabilities
        );

    }

}