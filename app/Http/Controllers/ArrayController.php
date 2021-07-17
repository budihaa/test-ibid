<?php

namespace App\Http\Controllers;

class ArrayController extends Controller
{
    public function index()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://gist.githubusercontent.com/Loetfi/fe38a350deeebeb6a92526f6762bd719/raw/9899cf13cc58adac0a65de91642f87c63979960d/filter-data.json');
        $parsedJson = json_decode($response->getBody());

        $array = [];
        foreach ($parsedJson->data->response->billdetails as $billdetail) {
            foreach ($billdetail->body as $body) {
                $cleanedValue = explode(':', preg_replace('/\s+/', '', $body));
                if ($cleanedValue[1] >= 100000) {
                    $array[] = $cleanedValue[1];
                }
            }
        }

        echo '<pre>';
        print_r($array);
        echo '<pre>';
    }
}
