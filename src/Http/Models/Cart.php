<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\CartFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Cart extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "carts";
    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return CartFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

}