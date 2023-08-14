<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\CategoryFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Category extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "categories";

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

}