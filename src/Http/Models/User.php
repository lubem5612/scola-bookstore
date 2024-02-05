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
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }


   public function book()
    {
        return $this->hasMany(Resource::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }

        public function journal()
    {
        return $this->hasMany(Journal::class);
    }

    public function festchrisft()
    {
        return $this->hasMany(Festchrisft::class);
    }

    public function conference_paper()
    {
        return $this->hasMany(ConferencePaper::class);
        
    }

    public function research_resource()
    {

         return $this->hasMany(ResourceCategory::class);
    }


    public function monograph()
    {
        return $this->hasMany(Monograph::class);
    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }


    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function reviewerRequest()
    {
        return $this->hasMany(ReviewerRequest::class);
    }
}