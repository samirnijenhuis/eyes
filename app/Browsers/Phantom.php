<?php

namespace App\Browsers;

use App\Contracts\Browser;
use Screen\Capture;

class Phantom implements Browser {

    /**
     * @param $group
     * @param $page
     * @param $size
     *
     * @return void
     */
    public function capture($group, $page, $size)
    {

        $screenCapture->setUrl($page['url']);
        $screenCapture->setBinPath('/usr/local/bin/');

        list($width, $height) = explode('x', $size, 2);
        $screenCapture->setWidth($width);
        $screenCapture->setHeight($height);
        $screenCapture->setImageType(\Screen\Image\Types\Png::FORMAT);
        $screenCapture->setDelay($page['wait-for-delay']);

        $filePath = "app/.eyes/" . $group . '/' . $size . '/' . $page['name'] . '.' . $screenCapture->getImageType()->getFormat();

        $screenCapture->save(storage_path( $filePath ));
    }



}