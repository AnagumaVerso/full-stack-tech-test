<?php

namespace App\Http\Services\Book;

use App\Models\Book;

class Update
{
    public function __invoke(array $data, Book $book): Book
    {
        $book->update([
            'title' => $data['title'] ?? $book->title,
            'author' => $data['author'] ?? $book->author,
            'rating' => $data['rating'] ?? $book->rating,
        ]);

        return $book;
    }
}
