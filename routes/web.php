<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    \Artisan::call('image:capture', ['name' => 'test-samir-asd']);
});
Route::get('/a', function () {
    $a = [
        [
            "dimension" => "1280x768",
            "filename" => "homepage",
            "before"     => ".eyes/before/1280x768/homepage.png",
            "after"      => ".eyes/after/1280x768/homepage.png",
            "difference" => ".eyes/diff_before_after/1280x768/homepage.png",
            "percentage" => 0.0028636163785533,
        ],
        [
            "dimension" => "1920x1080",
            "filename" => "homepage",
            "before"     => ".eyes/before/1920x1080/homepage.png",
            "after"      => ".eyes/after/1920x1080/homepage.png",
            "difference" => ".eyes/diff_before_after/1920x1080/homepage.png",
            "percentage" => 0.0018522167638461,
        ],
        [
            "dimension" => "320x480",
            "filename" => "homepage",
            "before"     => ".eyes/before/320x480/homepage.png",
            "after"      => ".eyes/after/320x480/homepage.png",
            "difference" => ".eyes/diff_before_after/320x480/homepage.png",
            "percentage" => 0.004484595439981,
        ],
        [
            "dimension" => "320x480",
            "filename" => "blabla",
            "before"     => ".eyes/before/320x480/homepage.png",
            "after"      => ".eyes/after/320x480/homepage.png",
            "difference" => ".eyes/diff_before_after/320x480/homepage.png",
            "percentage" => 0.004484595439981,
        ]

    ];

    return
        collect($a)->groupBy('filename')->transform(function($value, $key){
            return
                ["title" => $key, 'resolutions' => collect($value)->keyBy('dimension')->sort(), 'selected' => $value->first()['dimension']];
        })->values()->toArray();
});
