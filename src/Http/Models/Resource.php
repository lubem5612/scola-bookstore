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

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'resource_categories', 'resource_id', 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}