<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\PublisherFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Publisher extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "publishers";

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return PublisherFactory::new();
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function monograph()
    {
        return $this->hasMany(Monograph::class);
    }

        public function journal()
    {
        return $this->hasMany(Journal::class);
    }
}