<?php


namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\UserFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "users";

    protected $guarded = [
        "id"
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function author()
    {
        return $this->hasOne(Author::class);
    }

    public function reviewer()
    {
        return $this->hasOne(Reviewer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

}