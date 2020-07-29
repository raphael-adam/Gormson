<?php


namespace App\Repository;


use Illuminate\Support\Facades\Http;

class IcsDataRepository
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
