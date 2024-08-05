<?php

namespace App\Http\Services\Book;

use App\Models\Book;

class Index
{
    public function __invoke()
    {
        return Book::get();
    }
}
