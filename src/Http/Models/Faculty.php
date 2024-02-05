<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaBookstore\Database\Factories\FacultyFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Faculty extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'faculties';

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function departments() : HasMany
    {
        return $this->hasMany(Department::class);
    }

    protected static function newFactory()
    {
        return FacultyFactory::new();
    }
}