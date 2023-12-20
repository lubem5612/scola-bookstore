<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\ConferencePaperFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class ConferencePaper extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "conference_papers";
    
    protected $dates = ['conference_date'];

    protected $guarded = ['id'];

    protected $casts = [
        'contributors' => 'json',
        'keywords' => 'json',
        'institutional_affiliations' => 'json',
    ];

    protected static function newFactory()
    {
        return ConferencePaperFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}