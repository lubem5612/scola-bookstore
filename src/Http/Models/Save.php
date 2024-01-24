<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\SaveFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Save extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "saves";

    protected $guarded = [
        "id"
    ];

    protected static function newFactory()
    {
        return SaveFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}