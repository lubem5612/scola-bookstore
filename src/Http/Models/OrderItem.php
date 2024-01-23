<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\OrderDetailFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class OrderItem extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "order_items";

    protected $guarded = ['id'];
    protected static function newFactory()
    {
        return OrderDetailFactory::new();
    }


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}