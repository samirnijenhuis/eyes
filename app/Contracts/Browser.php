<?php

namespace App\Contracts;

interface Browser {

    /**
     * @param $group
     * @param $page
     * @param $size
     *
     * @return mixed
     */
    public function capture($group, $page, $size);
}