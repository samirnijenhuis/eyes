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
Route::get('/diff/{baseline}/{current}', function ($baseline, $current) {
   $view = file_get_contents(
       storage_path("app/.eyes/diff_{$baseline}_{$current}/output.html")
   );
   return $view;
});
