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


    public function book()
    {
        return $this->hasMany(Book::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function monograph()
    {
        return $this->hasMany(Monograph::class);
    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function conference_paper()
    {
        return $this->hasMany(ConferencePaper::class);
    }

    public function research_resource()
    {
        return $this->hasMany(ResearchResource::class);
    }

    public function festchrisft()
    {
        return $this->hasMany(Festchrisft::class);
    }
}