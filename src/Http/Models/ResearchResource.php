<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\ResearchResourceFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class ResearchResource extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "research_resources";

    protected $guarded = ['id'];
    
    protected $casts = [
        'keywords' => 'json',
        'contributors'=> 'json',
    ];


    protected static function newFactory()
    {
        return ResearchResourceFactory::new();
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

    // public function order()
    // {
    //     return $this->hasMany(Order::class, 'resource_id')->where('resource_type', 'ResearchResource');
    // }

}