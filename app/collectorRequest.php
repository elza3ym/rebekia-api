<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collectorRequest extends Model
{
    //
    public static function getColor($status)
    {
        if ($status == 'PENDING') return 'badge-warning';
        if ($status == 'ACCEPTED') return 'badge-primary';
        if ($status == 'CONFIRMED') return 'badge-turguoise';
        if ($status == 'DONE') return 'badge-success';

    }

    public function collector() {
        return $this->belongsTo(User::class, 'collector_id', 'id');
    }
    public function collectionPoint() {
        return $this->belongsTo(User::class, 'collectionPoint_id', 'id');
    }

    public function requestsItems(){
        return $this->belongsToMany(Item::class, 'collector_request_items', 'collectorRequest_id', 'item_id')->withPivot('count');
    }
    public function requestsItemsAvailable(){
        return $this->requestsItems()->where('available', '=', 1);
    }
}
