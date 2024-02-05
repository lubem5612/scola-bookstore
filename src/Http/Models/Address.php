<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\AuthorFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Address extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = 'addresses';

    protected $guarded = [ 'id' ];

    protected $hidden = ['created_at', 'updated_at'];

    protected static function newFactory()
    {
        return AuthorFactory::new();
    }

}