<?php


namespace Transave\ScolaBookstore\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class PaymentDetail extends Model
{
    use UUIDHelper, HasFactory;
    protected $table = "payment_details";

    protected $guarded = [ 'id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}