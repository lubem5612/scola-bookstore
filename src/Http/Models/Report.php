<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\JournalFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Report extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "reports";

    protected $guarded = ['id'];
    
    protected $casts = [
        'institutional_affiliations' => 'json',
        'keywords' => 'json',
        'contributors'=> 'json',
    ];


    protected static function newFactory()
    {
        return ReportFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

}