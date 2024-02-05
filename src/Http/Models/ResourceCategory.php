<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaBookstore\Database\Factories\ResourceCategoryFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class ResourceCategory extends Model
{
    use HasFactory, UUIDHelper;
    
    protected $table = "resource_categories";

    protected $guarded = ['id'];
    
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected static function newFactory()
    {
        return ResourceCategoryFactory::new();
    }

    public function resource() : BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}