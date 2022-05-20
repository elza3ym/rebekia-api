<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    public function citizenRequests(){
        return $this->belongsToMany(citizenRequest::class, 'citizen_request_items', 'item_id', 'citizenRequest_id');
    }
}
