<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function  show(Books $book) {
        return response()->json($book,200);
    }

    public function search(Request $request) {
        $request->validate(['key'=>'string|required']);

        $books = Book::where('author','like',"%$request->key%")
            ->orWhere('title','like',"%$request->key%")->get();

        return response()->json($books, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'author' => 'string|required',
            'title' => 'string|required',
            'genre' => 'string|required',
            'description' => 'string|required',
            'status' => 'string|required'
        ]);

        try {
            $book = Book::create($request->all());
            return response()->json($book, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ],500);
        }

    }

    public function update(Request $request, Book $book) {
        try {
            $book->update($request->all());
            return response()->json($book, 202);
        }catch(Exception $ex) {
            return response()->json(['message'=>$ex->getMessage()], 500);
        }
    }

    public function destroy(Book $book) {
        $book->delete();
        return response()->json(['message'=>'Book has deleted.'],202);
    }

    public function index() {
        $books = Book::orderBy('author')->get();
        return response()->json($books, 200);
    }
}
