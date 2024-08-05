<?php

namespace App\Http\Services\Book;

use App\Models\Book;

class Store
{
    public function __invoke(array $data): Book
    {
        return Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'rating' => $data['rating']
        ]);
    }
}
