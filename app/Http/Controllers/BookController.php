<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            return $this->respondNoContent(['message' => '']);
        }

        return $this->apiResponse([
            'success' => true,
            'message' => 'Fetch a book',
            'result' => $book,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            [
                'name' => $request->name,
            ],
            [
                'ip' => 'required|ip'
            ]
        );

        if ($validator->fails()) {
            return $this->respondValidationErrors($validator->errors());
        }

        $book = Book::create(['name' => $request->name]);
        return response($book);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required',
        ]);

        $book = Book::find($id)->update($validatedData);
        return response($book);
    }

    public function delete($id)
    {
        $books = Book::find($id)->delete();
        return response($books);
    }
}
