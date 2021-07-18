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

    /**
     * @OA\Get(
     *     path="/sample/{category}/things",
     *     operationId="/sample/category/things",
     *     tags={"yourtag"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="The category parameter in path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="criteria",
     *         in="query",
     *         description="Some optional other parameter",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
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

        $data = ['name' => $request->name];
        $book = tap(Book::find($id))->update($data)->first();
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
