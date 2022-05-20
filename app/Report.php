<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    public function reporter() {
        return $this->belongsTo(User::class, 'user_id', 'id');

    }
}
