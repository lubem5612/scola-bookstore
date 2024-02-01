<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaBookstore\Database\Factories\StateFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;
use Illuminate\Notifications\Notifiable;

class State extends Model
{
    use HasFactory, Notifiable, UUIDHelper;

    protected $table = "states";

    protected $guarded = [
        "id"
    ];

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function lgs() : HasMany
    {
        return $this->hasMany(Lg::class);
    }

    public function pickup()
    {
        return $this->hasMany(Pickup::class);
    }
    

    protected static function newFactory()
    {
        return StateFactory::new();
    }

}