<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Book::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Book::factory()
            ->count(5)
            ->create();
    }
}
