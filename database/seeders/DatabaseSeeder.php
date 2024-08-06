<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $genres = Genre::factory()->count(10)->create();

        Book::factory()->count(20)->create()->each(function ($book) use ($genres) {
            $book->genres()->attach(
                $genres->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
