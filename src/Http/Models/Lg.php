<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaBookstore\Database\Factories\LgFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;
use Illuminate\Notifications\Notifiable;

class Lg extends Model
{
       use HasFactory, Notifiable, UUIDHelper;

    protected $table = "lgs";

    protected $guarded = [
        "id"
    ];

    public function state() : BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function pickup()
    {
        return $this->hasMany(Pickup::class);
    }
    
    
    protected static function newFactory()
    {
        return LgFactory::new();
    }
}