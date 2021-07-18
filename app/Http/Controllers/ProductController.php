<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use ApiResponseTrait;

    private $db;

    public function __construct()
    {
        $firestore = app('firebase.firestore');
        $db = $firestore->database();
        $this->db = $db;
    }

    public function index()
    {
        $collections = $this->db->collection('products');
        $documents = $collections->documents();

        $products = [];
        foreach ($documents as $document) {
            foreach ($document->data() as $key => $data) {
                $products[] = [
                    'id' => $document->id(),
                    $key => $data,
                ];
            }
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'All Products',
            'result' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors(new ValidationException($validator));
        }

        $addedDocRef = $this->db->collection('products')->newDocument();
        $addedDocRef->set(['name' => $request->name]);

        $collections = $this->db->collection('products');
        $document = $collections->document($addedDocRef->id());
        $snapshot = $document->snapshot();

        $product = [];
        foreach ($snapshot->data() as $key => $data) {
            $product = [
                'id' => $document->id(),
                $key => $data,
            ];
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'Created Product',
            'result' => $product,
        ]);
    }

    public function show($id)
    {
        $document = $this->db->collection('products')->document($id);
        $snapshot = $document->snapshot();

        if (!$snapshot->exists()) {
            return $this->respondNotFound();
        }

        $product = [];
        foreach ($snapshot->data() as $key => $data) {
            $product = [
                'id' => $document->id(),
                $key => $data,
            ];
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'Fetch a product',
            'result' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors(new ValidationException($validator));
        }

        $document = $this->db->collection('products')->document($id);
        $snapshot = $document->snapshot();

        if (!$snapshot->exists()) {
            return $this->respondNotFound();
        }

        $document->set(['name' => $request->name]);

        $collections = $this->db->collection('products');
        $document = $collections->document($id);
        $snapshot = $document->snapshot();

        $product = [];
        foreach ($snapshot->data() as $key => $data) {
            $product = [
                'id' => $document->id(),
                $key => $data,
            ];
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'Updated Product',
            'result' => $product,
        ]);
    }

    public function delete($id)
    {
        $document = $this->db->collection('products')->document($id);
        $snapshot = $document->snapshot();

        if (!$snapshot->exists()) {
            return $this->respondNotFound();
        }

        $document->delete();

        return $this->apiResponse([
            'success' => true,
            'message' => 'Deleted Product',
        ], 204);
    }
}
