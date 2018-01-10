<?php

namespace App\Browsers;

use App\Contracts\Browser as BrowserContract;
use Screen\Capture;

/**
 * Class Phantom
 *
 * @package App\Browsers
 * @deprecated
 */
class Phantom implements BrowserContract {

    /**
     * @param $group
     * @param $page
     * @param $size
     *
     * @return void
     * @deprecated
     */
    public function capture($group, $page, $size)
    {
        $screenCapture = new Capture();
        $screenCapture->setBinPath('/usr/local/bin/');


        $screenCapture->setUrl($page['url']);

        list($width, $height) = explode('x', $size, 2);
        $screenCapture->setWidth($width);
        $screenCapture->setHeight($height);

        $screenCapture->setImageType(\Screen\Image\Types\Png::FORMAT);
        $screenCapture->setDelay($page['wait-for-delay']);

        $filePath = "app/.eyes/" . $group . '/' . $size . '/' . $page['name'] . '.' . $screenCapture->getImageType()->getFormat();

        $screenCapture->save(storage_path( $filePath ));
    }



}