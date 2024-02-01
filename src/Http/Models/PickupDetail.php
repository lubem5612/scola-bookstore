<?php

namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaBookstore\Database\Factories\PickupDetailFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use HasFactory, Notifiable, UUIDHelper;

    protected $guarded = [
        "id"
    ];

    protected $table = "pickup_details";

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
        return PickupDetailFactory::new();
    }

}