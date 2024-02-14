<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\ResourceFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Resource extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "resources";

    protected $guarded = ['id'];

    protected $casts = [
        'contributors' => 'json',
    ];

    protected static function newFactory()
    {
        return ResourceFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'resource_id')->where('resource_type', 'Book');
    }

}