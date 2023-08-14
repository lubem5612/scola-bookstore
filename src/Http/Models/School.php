<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\SchoolFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class School extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "schools";

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return SchoolFactory::new();
    }
}