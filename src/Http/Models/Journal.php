<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\JournalFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Journal extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "journals";

    protected $guarded = ['id'];
    
    protected $casts = [
        'editors' => 'json',
        'editorial_board_members' => 'json',
    ];


    protected static function newFactory()
    {
        return JournalFactory::new();
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

    //     public function order()
    // {
    //     return $this->hasMany(Order::class, 'resource_id')->where('resource_type', 'Journal');
    // }

}