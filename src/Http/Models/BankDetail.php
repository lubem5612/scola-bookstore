<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\BankDetailFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class BankDetail extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;

    protected $table = "banks";

    protected $guarded = ['id'];


    protected static function newFactory()
    {
        return BankDetailFactory::new();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}