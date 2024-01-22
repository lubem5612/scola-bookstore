<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\SaleFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Sale
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "sales";

    protected $guarded = [
        "id"
    ];

    protected static function newFactory()
    {
        return SaleFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function book()
    // {
    //     return $this->belongsTo(Book::class);
    // }
}