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
    protected $table = "publishers";

    protected $guarded = [
        "id"
    ];

    protected static function newFactory()
    {
        return AuthorFactory::new();
    }


    public function book()
    {
        return $this->hasMany(Resource::class);
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
        return $this->hasMany(ResourceCategory::class);
    }

    public function festchrisft()
    {
        return $this->hasMany(Festchrisft::class);
    }
}