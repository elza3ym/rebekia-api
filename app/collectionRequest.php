<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collectionRequest extends Model
{
    //

    public function factory() {
        return $this->belongsTo(User::class, 'factory_id', 'id');
    }
    public function collectionPoint() {
        return $this->belongsTo(User::class, 'collectionPoint_id', 'id');
    }

    public function requestsItems(){
        return $this->belongsToMany(Item::class, 'collection_request_items', 'collectionRequest_id', 'item_id')->withPivot('count');
    }
    public function requestsItemsAvailable(){
        return $this->requestsItems()->where('available', '=', 1);
    }
}
