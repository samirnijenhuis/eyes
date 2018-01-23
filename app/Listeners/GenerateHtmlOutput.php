<?php

namespace App\Listeners;

use App\Events\ImagesCompared;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager as Storage;

class GenerateHtmlOutput
{
    /**
     * @var \Illuminate\Filesystem\FilesystemManager
     */
    private $storage;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Filesystem\FilesystemManager $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Handle the event.
     *
     * @param  ImagesCompared  $event
     * @return void
     */
    public function handle(ImagesCompared $event)
    {
        $json = $this->outputToJson($event->output);

        $view = view('difference')->with('difference' , $json);
        $this->storage->put(".eyes/diff_{$event->before}_{$event->after}/output.html", $view);
    }

    /**
     * Transform the output to a custom defined JSON string.
     * @param array $output
     *
     * @return string
     */
    private function outputToJson(array $output)
    {
        $json = collect($output)->groupBy('filename')->transform(function($value, $key){
            return [
                "title" => $key,
                'resolutions' => collect($value)->keyBy('dimension'),
                'selected' => $value->first()['dimension']
            ];
        })->values()->toJson();

        return $json;
    }

}
