<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaBookstore\Database\Factories\ReviewFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Review extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "reviews";

    protected $guarded = [
        "id"
    ];

    public function resource() : BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return ReviewFactory::new();
    }
}