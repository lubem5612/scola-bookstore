<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\OrderDetailFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class OrderDetail extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "order_details";

    protected $guarded = ['id'];
    protected static function newFactory()
    {
        return OrderDetailFactory::new();
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}