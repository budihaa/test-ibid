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

/**
 * @OA\Get(
 *     summary="Returns all products",
 *     path="/api/products",
 *     operationId="/api/products",
 *     tags={"Products API Endpoint (Soal no. 3 )"},
 *     @OA\Response(
 *         response="200",
 *         description="Success Response",
 *         @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="All products"),
 *       @OA\Property(
 *             property="result", type="array", example={{
 *                  "id": "114eb06346dd48c48c5a",
 *                  "name": "Kopi Kapal Api",
 *                }, {
 *                              "id": "DF2yxPnSIhxggzMZv7kf",
 *           "name": "Indomie",
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="id",
 *                         type="string",
 *                         example="DF2yxPnSIhxggzMZv7kf"
 *                      ),
 *                      @OA\Property(
 *                         property="message",
 *                         type="string",
 *                         example="Indomie"
 *                      ),
 *                ),
 *             ),
 *        ),
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized.",
 *     ),
 * )
 */

/**
 * @OA\Get(
 *     summary="Fetch a product by ID",
 *     path="/api/products/{id}",
 *     operationId="/api/products/id",
 *     tags={"Products API Endpoint (Soal no. 3 )"},
 *      @OA\Parameter(
 *         description="ID of product to return",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *           type="string",
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Success response",
 *         @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="All products"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "id": "DF2yxPnSIhxggzMZv7kf",
 *                  "name": "Indomie",
 *                },
 *             ),
 *        ),
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized.",
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Resource not found.",
 *          @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Not Found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       ),
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     summary="Create new product",
 *     path="/api/products/",
 *     operationId="/api/products/",
 *     tags={"Products API Endpoint (Soal no. 3 )"},
 *     @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(
 *           property="name",
 *           description="Product Name",
 *           type="string",
 *         ),
 *       ),
 *     ),
 *   ),
 *     @OA\Response(
 *         response="200",
 *         description="Success response",
 *         @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="Product Created"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "id": "DF2yxPnSIhxggzMZv7kf",
 *                  "name": "Indomie",
 *                },
 *             ),
 *        ),
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized.",
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Input validation errors",
 *          @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Not Found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       @OA\Property(
 *             property="errors", type="array", example={{
 *                  "field": "name",
 *                  "message": "The name input is required.",
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="field",
 *                         type="string",
 *                         example="The name field is required."
 *                      ),
 *                ),
 *             ),
 *       ),
 *     ),
 * )
 */

/**
 * @OA\Put(
 *     summary="Update product",
 *     path="/api/products/{id}",
 *     operationId="/api/products/update",
 *     tags={"Products API Endpoint (Soal no. 3 )"},
 *        @OA\Parameter(
 *         description="ID of product to update",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *           type="string",
 *         )
 *     ),
 *     @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(
 *           property="name",
 *           description="Product Name",
 *           type="string",
 *         ),
 *       ),
 *     ),
 *   ),
 *     @OA\Response(
 *         response="200",
 *         description="Success response",
 *         @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="Product Created"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "id": "DF2yxPnSIhxggzMZv7kf",
 *                  "name": "Indomie",
 *                },
 *             ),
 *        ),
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized.",
 *     ),
 *       @OA\Response(
 *         response="404",
 *         description="Resource not found.",
 *          @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Not Found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       ),
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Input validation errors",
 *          @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Not Found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       @OA\Property(
 *             property="errors", type="array", example={{
 *                  "field": "name",
 *                  "message": "The name input is required.",
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="field",
 *                         type="string",
 *                         example="The name field is required."
 *                      ),
 *                ),
 *             ),
 *       ),
 *     ),
 * )
 */

/**
 * @OA\Delete(
 *     summary="Delete a product by ID",
 *     path="/api/products/{id}",
 *     operationId="deletePet",
 *     tags={"Products API Endpoint (Soal no. 3 )"},
 *      @OA\Parameter(
 *         description="ID of product to delete",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *           type="string",
 *         )
 *     ),
 *     @OA\Response(
 *         response="204",
 *         description="Success response",
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized.",
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Resource not found.",
 *          @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=false),
 *       @OA\Property(property="message", type="string", example="Not Found"),
 *       @OA\Property(property="result", type="null", example="null"),
 *       @OA\Property(property="error_code", type="integer", example="1"),
 *       ),
 *     ),
 * )
 */
