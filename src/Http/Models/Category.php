<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\CategoryFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Category extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "bs_categories";

    protected $guarded = [
        "id"
    ];

    protected $hidden = [ 'created_at', 'updated_at' ];

    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'bs_resource_categories', 'category_id', 'resource_id');
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}