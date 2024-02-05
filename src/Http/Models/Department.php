<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaBookstore\Database\Factories\DepartmentFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Department extends Model
{
    use HasFactory, UUIDHelper;

    protected $guarded = ['id'];

    protected $table = 'departments';

    protected $hidden = ['created_at', 'updated_at'];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }

}