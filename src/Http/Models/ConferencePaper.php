<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\ConferencePaperFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class ConferencePaper extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "conference_papers";
    
    protected $dates = ['conference_date'];

    protected $guarded = ['id'];

    protected $casts = [
        'other_authors' => 'json',
        'keywords' => 'json',
        'references' => 'json',
    ];

    protected static function newFactory()
    {
        return ConferencePaperFactory::new();
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