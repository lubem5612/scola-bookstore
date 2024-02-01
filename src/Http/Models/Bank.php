<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaBookstore\Database\Factories\BankFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Bank extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;

    protected $table = "banks";

    protected $guarded = ['id'];


    protected static function newFactory()
    {
        return BankFactory::new();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function bank_details()
    {
        return $this->hasMany(BankDetail::class);
    }

}