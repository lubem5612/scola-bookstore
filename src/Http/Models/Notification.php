<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\NotificationFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class Notification extends Model
{
    use HasFactory, UUIDHelper;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'notifications';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    protected static function newFactory()
    {
        return NotificationFactory::new();
    }
}