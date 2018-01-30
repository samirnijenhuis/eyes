<?php
namespace App\Services;

use Imagick;
use Illuminate\Filesystem\FilesystemManager as Storage;


class ImageService {

    /**
     * @var \Illuminate\Filesystem\FilesystemManager
     */
    private $storage;

    /**
     * ImageService constructor.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }
    /**
     * @param $base_file
     * @param $baseline
     * @param $current
     *
     * @return array
     */
    public function compare($base_file, $baseline, $current)
    {

        $image1 = $this->prepareBaseline($base_file);
        $current_file = str_replace(".eyes/{$baseline}", ".eyes/{$current}", $base_file);


        // Load the corresponding images.
        $image2 = new Imagick();
        $image2->readImage(storage_path("app/" .$current_file));

        // Compare image 1 with image 2.
        $result = $image1->compareImages($image2, Imagick::METRIC_ROOTMEANSQUAREDERROR);
        $result[0]->setImageFormat("png");

        $diff_file = str_replace(".eyes/{$current}", ".eyes/diff_{$baseline}_{$current}" ,$current_file);

        // Write file.
        $this->storage->put($diff_file, $result[0]);


        return [
            "filename" => pathinfo($base_file, PATHINFO_FILENAME),
            "dimension" => basename(dirname($base_file)),
            "before" => storage_path("app/" . $base_file),
            "after" => storage_path("app/" . $current_file),
            "difference" => storage_path("app/" . $diff_file),
            "percentage" => round($result[1] * 100, 2),
        ];
    }


    protected function prepareBaseline($base_file, $transparent = false)
    {
        $image = new Imagick();

        if($transparent) {
            // Set Lowlight (resulting background) to transparent, not "original image with a bit of opacity"
            $image->setOption('lowlight-color','transparent');

            // Switch the default compose operator to Src, like in example
            $image->setOption('compose', 'Src');
        }


        $image->setOption('fuzz', '100');
        $image->readImage(storage_path( "app/" .$base_file));

        return $image;

    }
}