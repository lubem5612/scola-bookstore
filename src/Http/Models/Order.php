<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\OrderFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Order extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;

    
    protected $table = "orders";

    protected $guarded = [
        "id"
    ];

    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pickup()
    {
        return $this->hasMany(Pickup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}