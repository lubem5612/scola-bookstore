<?php

namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\PickupFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Pickup extends Model
{
    use HasFactory, UUIDHelper;

    protected $guarded = [
        "id"
    ];

    protected $table = "pickups";

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function lg()
    {
        return $this->belongsTo(Lg::class, 'lg_id');
    }

    public function order() 
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    protected static function newFactory()
    {
        return PickupFactory::new();
    }

}