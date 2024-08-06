<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Book;
use App\Models\Genre;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Controllers\BookController;
use App\Http\Services\Book\Update;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    private function validateRequest(UpdateRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        $request->setValidator($validator);
        $validator->validate();
    }

    public function test_update_book_without_genres()
    {
        // Arrange
        $book = Book::factory()->create();
        $data = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
        ];

        $request = UpdateRequest::create("/api/books/{$book->id}", 'PUT', $data);
        $this->validateRequest($request);

        $updateService = Mockery::mock(Update::class);
        $updateService->shouldReceive('__invoke')->andReturnUsing(function ($data, $book) {
            $book->update($data);
            return $book;
        });

        $controller = new BookController();

        // Inject the mocked service into the controller
        $response = $controller->update($request, $updateService, $book);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
        ]);
    }

    public function test_update_book_with_genres()
    {
        // Arrange
        $book = Book::factory()->create();
        $genres = Genre::factory()->count(2)->create();
        $data = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
            'genres' => $genres->pluck('id')->toArray(),
        ];

        $request = UpdateRequest::create("/api/books/{$book->id}", 'PUT', $data);
        $this->validateRequest($request);

        $updateService = Mockery::mock(Update::class);
        $updateService->shouldReceive('__invoke')->andReturnUsing(function ($data, $book) {
            $genres = $data['genres'];
            unset($data['genres']);
            $book->update($data);
            $book->genres()->sync($genres);
            return $book;
        });

        $controller = new BookController();

        // Inject the mocked service into the controller
        $response = $controller->update($request, $updateService, $book);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
        ]);
        foreach ($genres as $genre) {
            $this->assertDatabaseHas('book_genre', [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
        }
    }

    public function test_update_book_removes_old_genres()
    {
        // Arrange
        $book = Book::factory()->create();
        $oldGenres = Genre::factory()->count(2)->create();
        $book->genres()->attach($oldGenres);

        $newGenres = Genre::factory()->count(2)->create();
        $data = [
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
            'genres' => $newGenres->pluck('id')->toArray(),
        ];

        $request = UpdateRequest::create("/api/books/{$book->id}", 'PUT', $data);
        $this->validateRequest($request);

        $updateService = Mockery::mock(Update::class);
        $updateService->shouldReceive('__invoke')->andReturnUsing(function ($data, $book) {
            $genres = $data['genres'];
            unset($data['genres']);
            $book->update($data);
            $book->genres()->sync($genres);
            return $book;
        });

        $controller = new BookController();

        // Inject the mocked service into the controller
        $response = $controller->update($request, $updateService, $book);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'rating' => 4,
        ]);
        foreach ($oldGenres as $genre) {
            $this->assertDatabaseMissing('book_genre', [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
        }
        foreach ($newGenres as $genre) {
            $this->assertDatabaseHas('book_genre', [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
