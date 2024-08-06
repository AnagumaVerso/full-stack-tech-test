<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\DestroyRequest;
use App\Http\Requests\Book\IndexRequest;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Services\Book\Destroy;
use App\Http\Services\Book\Index;
use App\Http\Services\Book\Store;
use App\Http\Services\Book\Update;
use App\Models\Book;

class BookController extends Controller
{
    public function index(IndexRequest $request, Index $index)
    {
        return response()->json([
            'message' => 'Successfully fetched the books.',
            'data' => $index()
        ]);
    }

    public function store(StoreRequest $request, Store $store)
    {
        $book = $store($request->validated());

        if ($request->has('genres')) {
            $book->genres()->attach($request->genres);
        }

         return response()->json([
            'message' => 'Successfully stored the book.',
            'data' => $book
        ]);
    }

    public function update(UpdateRequest $request, Update $update, Book $book)
    {
        $updatedBook = $update($request->validated(), $book);

        if ($request->has('genres')) {
            $updatedBook->genres()->sync($request->genres);
        }

        return response()->json([
            'message' => 'Successfully updated the book.',
            'data' => $updatedBook
        ]);
    }

    public function destroy(DestroyRequest $request, Destroy $destroy, Book $book)
    {
        // TODO: Retain option for admins to completely remove books?
        // $destroy($book);
        $book->delete();

        return response()->json([
            'message' => 'Successfully deleted the book.',
        ]);
    }
}
