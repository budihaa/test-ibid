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

        $book = Book::find($id)->update(['name' => $request->name]);
        return $this->respondCreated([
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

        return $this->apiResponse([], 204);
    }
}
