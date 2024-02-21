<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\TransactionFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Transaction extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "transactions";

    protected $guarded = [
        "id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return TransactionFactory::new();
    }

}