<?php

namespace App\Browsers\Dusk\Traits;

use RuntimeException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

trait SupportsPhantom
{
    /**
     * The path to the custom Phantomdriver binary.
     *
     * @var string|null
     */
    protected static $phantomDriver;

    /**
     * The Phantomdriver process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected static $phantomProcess;

    /**
     * Start the Phantomriver process.
     *
     * @throws \RuntimeException if the driver file path doesn't exist.
     *
     * @return void
     */
    public static function startPhantomDriver()
    {
        static::$phantomProcess = static::buildPhantomProcess();

        static::$phantomProcess->start();

        // We wait with execution of the scripts untill we see that the output shows us it's running.
        while( ! str_contains(static::$phantomProcess->getOutput(), 'running on port')){
            sleep(1);
        }

        static::afterClass(function () {
            static::stopPhantomDriver();
        });
    }

    /**
     * Stop the Phantomdriver process.
     *
     * @return void
     */
    public static function stopPhantomDriver()
    {
        if (static::$phantomProcess) {
            static::$phantomProcess->stop();
        }
    }

    /**
     * Build the process to run the PhantomDriver.
     *
     * @throws \RuntimeException if the driver file path doesn't exist.
     *
     * @return \Symfony\Component\Process\Process
     */
    protected static function buildPhantomProcess()
    {
        return (new ProcessBuilder())
            ->setPrefix(realpath('/usr/local/bin/phantomjs')) // @todo modular OR fixed in package.
            ->setArguments(['--webdriver=8910'])
            ->getProcess();
    }

    /**
     * Set the path to the custom Phantomdriver.
     *
     * @param  string  $path
     * @return void
     */
    public static function usePhantomdriver($path)
    {
        static::$phantomDriver = $path;
    }
}
