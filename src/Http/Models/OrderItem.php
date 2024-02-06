<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\OrderItemFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class OrderItem extends Model
{
    use HasFactory, UUIDHelper;
    
    protected $table = "order_items";

    protected $guarded = ['id'];
    
    protected static function newFactory()
    {
        return OrderItemFactory::new();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

}