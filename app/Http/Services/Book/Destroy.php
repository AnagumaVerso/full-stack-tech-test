<?php

namespace App\Http\Services\Book;

use App\Models\Book;

class Destroy
{
    public function __invoke(Book $book): void
    {
        $book->delete();
    }
}
