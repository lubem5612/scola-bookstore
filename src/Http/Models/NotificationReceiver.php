<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Database\Factories\NotificationReceiverFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class NotificationReceiver extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'notification_receivers';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    protected static function newFactory()
    {
        return NotificationReceiverFactory::new();
    }
}