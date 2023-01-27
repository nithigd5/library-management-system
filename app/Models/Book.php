<?php

namespace App\Models;

use App\Traits\BookTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $author
 * @property mixed $name
 * @property float $price
 * @property int $version
 * @property bool $is_download_allowed
 * @property string $mode
 * @property string $image
 * @property string $book_path
 * @method static Book create(array $array)
 */
class Book extends Model
{
    use HasFactory, BookTrait;
    protected $fillable = ['name', 'author', 'price', 'version', 'mode', 'image', 'book_path', 'is_download_allowed'];
}
