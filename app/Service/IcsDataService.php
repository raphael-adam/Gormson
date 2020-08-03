<?php


namespace App\Service;

use Illuminate\Support\Facades\Http;

class IcsDataService
{
    private $url;

    public function __construct()
    {
        $this->url = env('TIMETAPE_API_URL');
    }

    public function get() {
        return Http::get($this->url);
    }
}
