<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $books = Book::all();
        return $this->apiResponse([
            'success' => true,
            'message' => 'All Books',
            'result' => $books,
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return $this->respondNotFound();
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'Fetch a book',
            'result' => $book,
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

        $book = Book::create(['name' => $request->name]);
        return $this->respondCreated([
            'success' => true,
            'message' => 'Book Created',
            'result' => $book
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

        $book = Book::find($id);
        if (!$book) {
            return $this->respondNotFound();
        }

        $data = ['name' => $request->name];
        $book = tap($book)->update($data)->first();
        return $this->apiResponse([
            'success' => true,
            'message' => 'Book Updated',
            'result' => $book
        ]);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return $this->respondNotFound();
        }

        $book->delete();

        return $this->apiResponse([
            'success' => true,
            'message' => 'Deleted Book',
        ], 204);
    }
}

/**
 * @OA\Get(
 *     summary="Returns all books",
 *     path="/api/books",
 *     operationId="/api/books",
 *     tags={"Books API Endpoint (Soal no. 1)"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response="200",
 *         description="Success Response",
 *         @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="All Books"),
 *       @OA\Property(
 *             property="result", type="array", example={{
 *                  "_id": "60f28b0ceb22744a8f128df2",
 *                  "name": "Manusia Setengah Salmon",
 *                  "created_at": "2021-07-18T02:28:28.833000Z",
 *                  "updated_at": "2021-07-18T02:28:28.833000Z",
 *                }, {
 *                              "_id": "60f29f3896ea731bdf08fcf2",
 *           "name": "Zxcvasdf",
 *           "updated_at": "2021-07-17T09:13:27.173000Z",
 *           "created_at": "2021-07-17T09:13:27.173000Z"
 *                }},
 *              @OA\Items(
 *                      @OA\Property(
 *                         property="_id",
 *                         type="string",
 *                         example="60f28b0ceb22744a8f128df2"
 *                      ),
 *                      @OA\Property(
 *                         property="message",
 *                         type="string",
 *                         example="Manusia Setengah Salmon"
 *                      ),
 *                      @OA\Property(
 *                         property="created_at",
 *                         type="date",
 *                         example="2021-07-18T02:28:28.833000Z"
 *                      ),
 *                      @OA\Property(
 *                         property="updated_at",
 *                         type="date",
 *                         example="2021-07-18T02:28:28.833000Z"
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
 *     summary="Fetch a book by ID",
 *     path="/api/books/{id}",
 *     operationId="/api/books/id",
 *     tags={"Books API Endpoint (Soal no. 1)"},
 *     security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         description="ID of book to return",
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
 *       @OA\Property(property="message", type="string", example="All Books"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "_id": "60f28b0ceb22744a8f128df2",
 *                  "name": "Manusia Setengah Salmon",
 *                  "created_at": "2021-07-18T02:28:28.833000Z",
 *                  "updated_at": "2021-07-18T02:28:28.833000Z",
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
 *     summary="Create new book",
 *     path="/api/books/",
 *     operationId="/api/books/",
 *     tags={"Books API Endpoint (Soal no. 1)"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         @OA\Property(
 *           property="name",
 *           description="Book Name",
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
 *       @OA\Property(property="message", type="string", example="Book Created"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "_id": "60f28b0ceb22744a8f128df2",
 *                  "name": "Manusia Setengah Salmon",
 *                  "created_at": "2021-07-18T02:28:28.833000Z",
 *                  "updated_at": "2021-07-18T02:28:28.833000Z",
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
 *     summary="Update book",
 *     path="/api/books/{id}",
 *     operationId="/api/books/update",
 *     tags={"Books API Endpoint (Soal no. 1)"},
 *     security={{"bearerAuth":{}}},
 *        @OA\Parameter(
 *         description="ID of book to update",
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
 *           description="Book Name",
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
 *       @OA\Property(property="message", type="string", example="Book Created"),
 *       @OA\Property(
 *             property="result", type="object", example={
 *                  "_id": "60f28b0ceb22744a8f128df2",
 *                  "name": "Manusia Setengah Salmon",
 *                  "created_at": "2021-07-18T02:28:28.833000Z",
 *                  "updated_at": "2021-07-18T02:28:28.833000Z",
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
 *     summary="Delete a book by ID",
 *     path="/api/books/{id}",
 *     operationId="deletePet",
 *     tags={"Books API Endpoint (Soal no. 1)"},
 *     security={{"bearerAuth":{}}},
 *      @OA\Parameter(
 *         description="ID of book to delete",
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
