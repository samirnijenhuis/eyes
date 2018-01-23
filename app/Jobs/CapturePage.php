<?php

namespace App\Jobs;

use App\Contracts\Browser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CapturePage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $page;
    public $size;

    /**
     * Create a new job instance.
     *
     * @param $name
     * @param $page
     * @param $size
     */
    public function __construct($name, $page, $size)
    {
        $this->name = $name;
        $this->page = $page;
        $this->size = $size;

    }

    /**
     * Execute the job.
     *
     * @param \App\Contracts\Browser $browser
     *
     * @return void
     */
    public function handle(Browser $browser)
    {
        $browser->capture($this->name, $this->page, $this->size);
    }
}
