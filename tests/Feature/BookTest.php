<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Whether Admin Can create a book
     *
     * @return void
     */
    public function test_can_create_book(): void
    {
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);

        Storage::fake();

        $user = User::factory()->create()->assignRole('admin');

        //Test admin Can Create an Online book
        $book = [
            'name' => 'Book Name' ,
            'author' => 'Book Author' ,
            'price' => 50.50 ,
            'version' => 5 ,
            'book' => $bookFile = UploadedFile::fake()->create('book.pdf' , 100 , 'application/pdf') ,
            'image' => $image = UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100) ,
            'mode' => 'online' ,
            'is_download_allowed' => true
        ];
        $response = $this->actingAs($user)->post(route('books.store') , $book);
        $response->assertStatus(200);

        $book['book_path'] = 'books/' . $bookFile->hashName();
        unset($book['book']);
        $book['image'] = 'public/data/books/front-covers/' . $image->hashName();

        $this->assertDatabaseHas('books' , $book);
        Storage::assertExists($book['book_path']);
        Storage::assertExists($book['image']);

        //Assert admin cannot create an Invalid offline book
        $book = [
            'author' => 'Book Author' ,
            'price' => 50.50 ,
            'version' => 5 ,
            'mode' => 'offline' ,
        ];
        $response = $this->actingAs($user)->post(route('books.store') , $book);
        $response->assertSessionHasErrors(['name' , 'image']);

        //Test admin cannot an Offline Book
        $book = [
            'name' => 'Book Name 2' ,
            'author' => 'Book Author' ,
            'price' => 50.50 ,
            'version' => 5 ,
            'mode' => 'offline' ,
            'image' => $image = UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100) ,
        ];
        $response = $this->actingAs($user)->post(route('books.store') , $book);
        $response->assertStatus(200);

        $book['image'] = 'public/data/books/front-covers/' . $image->hashName();
        $book['is_download_allowed'] = false;

        $this->assertDatabaseHas('books' , $book);
        Storage::assertExists($book['image']);
    }

    /**
     * Test Whether Admin Can Update a book
     *
     * @return void
     */
    public function test_can_update_book(): void
    {
        $this->seed([PermissionSeeder::class , RoleSeeder::class]);

        Storage::fake();

        $user = User::factory()->create()->assignRole('admin');

        $book = Book::factory()->create();

        //Test admin can update a book (offline)
        $new_book['name'] = 'Book New Name';
        $new_book['author'] = 'Book New Author';
        $new_book['price'] = 55.60;
        $new_book['version'] = 6;
        $new_book['mode'] = 'offline';
        $new_book['image'] = UploadedFile::fake()->image('thumbnail.jpg' , 100 , 100);

        $response = $this->actingAs($user)->put(route('books.update', $book->id) , $new_book);
        $response->assertStatus(200);

        $new_book['image'] = 'public/data/books/front-covers/' . $new_book['image']->hashName();

        $this->assertDatabaseHas('books', $new_book);
        Storage::assertExists($new_book['image']);

        //Test admin cannot update a book to online without PDF of Book
        $new_book = array();
        $new_book['name'] = 'Book New Name';
        $new_book['author'] = 'Book New Author';
        $new_book['price'] = 55.60;
        $new_book['version'] = 6;
        $new_book['mode'] = 'online';

        $response = $this->actingAs($user)->put(route('books.update', $book->id) , $new_book);

        $response->assertSessionHasErrors(['book_file', 'is_download_allowed']);

        //Test admin can update a book to online with PDF
        $new_book = array();
        $new_book['name'] = 'Book New Name 2';
        $new_book['author'] = 'Book New Author 2';
        $new_book['price'] = 55.60;
        $new_book['version'] = 6;
        $new_book['mode'] = 'online';
        $new_book['book_file'] = UploadedFile::fake()->create('book.pdf' , 100 , 'application/pdf');
        $new_book['is_download_allowed'] = false;

        $response = $this->actingAs($user)->put(route('books.update', $book->id) , $new_book);
        $response->assertStatus(200);

        $new_book['book_path'] = 'books/'.$new_book['book_file']->hashName();
        unset($new_book['book_file']);

        $this->assertDatabaseHas('books', $new_book);
        Storage::assertExists($new_book['book_path']);
    }
}
