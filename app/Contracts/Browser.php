<?php

namespace App\Contracts;

use App\Repositories\PageRepository;

interface Browser {

    /**
     * @param $group
     * @param $page
     * @param $size
     *
     * @return mixed
     */
    public function capture($group, PageRepository $page, $size);
}