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
        'other_authors' => 'json',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }


    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class);
    }


    protected $dates = ['publish_date'];
}