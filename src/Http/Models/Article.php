<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\ArticleFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Article extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "articles";

    protected $guarded = ['id'];

    protected $casts = [
        'other_authors' => 'json',
        'keywords' => 'json',
        'references' => 'json',
    ];
    
    protected $dates = ['publish_date'];



    protected static function newFactory()
    {
        return ArticleFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    // public function carts()
    // {
    //     return $this->hasMany(Cart::class);
    // }


    // public function saves()
    // {
    //     return $this->hasMany(Save::class);
    // }

    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }

    // public function orderdetails()
    // {
    //     return $this->hasMany(OrderDetail::class);
    // }

}