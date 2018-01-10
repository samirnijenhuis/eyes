<?php

namespace App\Browsers\Traits;

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
        dd(        static::$phantomProcess , static::$phantomDriver );

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
            ->setPrefix(realpath('/usr/local/bin/phantomjs')) // Replace with path to phantomjs bin
            ->setArguments(['-w'])
            ->getProcess();
//        return (new Process(
//            '/usr/local/Cellar/phantomjs/2.1.1/bin/phantomjs --webdriver=8910'
//            [realpath('/usr/local/bin/phantomjs') . ' --webdriver=8910' ]
//        ));

//        return (new ProcessBuilder())
//            ->setPrefix('/usr/local/bin/phantomjs') // Replace with path to phantomjs bin
//            ->setArguments(['--webdriver=8910'])
//            ->getProcess();
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
