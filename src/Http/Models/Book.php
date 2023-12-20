<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\BookFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Book extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;

    protected $table = "books";

    protected $guarded = ['id'];

    protected $casts = [
        'contributors' => 'json',
    ];

    protected static function newFactory()
    {
        return BookFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

}