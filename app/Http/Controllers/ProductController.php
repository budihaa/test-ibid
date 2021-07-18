<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Firestore;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $firestore = app('firebase.firestore');
        echo '<pre>';
        print_r($firestore);
        echo '<pre>';

        // return $this->apiResponse([
        //     'success' => true,
        //     'message' => 'All Books',
        //     'result' => $products,
        // ]);
    }
}
