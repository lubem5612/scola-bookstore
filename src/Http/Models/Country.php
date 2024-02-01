<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaBookstore\Database\Factories\CountryFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use HasFactory, Notifiable, UUIDHelper;

    protected $guarded = [
        "id"
    ];

    protected $table = "countries";

    public function state() : HasMany
    {
        return $this->hasMany(State::class);
    }

    public function pickup_details()
    {
        return $this->hasMany(PickupDetail::class);
    }
    

    public function bank() : HasMany
    {
        return $this->hasMany(Bank::class);
    }

    protected static function newFactory()
    {
        return CountryFactory::new();
    }

}