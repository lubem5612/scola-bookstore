<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\ReviewerRequestFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class ReviewerRequest extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens; 
    
    protected $guarded = ['id'];
    
    protected $casts = [
        'previous_projects' => 'json',
    ]; 
    
    
    protected static function newFactory()
    {
        return ReviewerRequestFactory::new();
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
