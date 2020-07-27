<?php


namespace App\Leave;


use Illuminate\Support\Facades\Http;

class GetIcsData
{
    private $url;
    private $response = [];

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;

    }

    public function __construct()
    {
        $this->url = env('TIMETAPE_API_URL');
        $this->response = [];
    }

    public function icsData() {
        return $this->receiveIcsData();
    }

    private function receiveIcsData() {
        return Http::get($this->getUrl());
    }

}
