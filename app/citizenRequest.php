<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class citizenRequest extends Model
{
    //
    public function collector() {
        return $this->belongsTo(User::class, 'collector_id', 'id');
    }

    public function citizen() {
        return $this->belongsTo(User::class, 'citizen_id', 'id');
    }

    public function requestsItems(){
        return $this->belongsToMany(Item::class, 'citizen_request_items', 'citizenRequest_id', 'item_id')->withPivot('count');
    }
    public function requestsItemsAvailable(){
        return $this->requestsItems()->where('available', '=', 1);
    }
}
