<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\AuthorFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Author extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "authors";

    protected $guarded = [
        "id"
    ];

    protected static function newFactory()
    {
        return AuthorFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

}