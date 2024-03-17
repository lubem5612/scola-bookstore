<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaBookstore\Database\Factories\ReviewerFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Reviewer extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = 'reviewers';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return ReviewerFactory::new();
    }
}