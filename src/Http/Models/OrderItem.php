<?php

namespace Transave\ScolaBookstore\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaBookstore\Database\Factories\OrderItemFactory;
use Transave\ScolaBookstore\Helpers\UUIDHelper;

class OrderItem extends Model
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    protected $table = "order_items";

    protected $guarded = ['id'];
    
    protected static function newFactory()
    {
        return OrderItemFactory::new();
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


        public function book()
    {
        return $this->belongsTo(Resource::class, 'resource_id')->where('resource_type', 'Book');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'resource_id')->where('resource_type', 'Report');
    }

        public function journal()
    {
        return $this->belongsTo(Journal::class, 'resource_id')->where('resource_type', 'Journal');
    }

    public function festchrisft()
    {
        return $this->belongsTo(Festchrisft::class, 'resource_id')->where('resource_type', 'Festchrisft');
    }

    public function conference_paper()
    {
        return $this->belongsTo(ConferencePaper::class, 'resource_id')->where('resource_type', 'ConferencePaper');
        
    }

    public function research_resource()
    {

         return $this->belongsTo(ResourceCategory::class, 'resource_id')->where('resource_type', 'ResearchResource');
    }


    public function monograph()
    {
        return $this->belongsTo(Monograph::class, 'resource_id')->where('resource_type', 'Monograph');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'resource_id')->where('resource_type', 'Article');
    }

}