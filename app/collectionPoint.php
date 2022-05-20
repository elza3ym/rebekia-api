<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collectionPoint extends Model
{
    //
    public static function getRequestsStats() {
        $user = \Auth::user();

        $requests = collectorRequest::where('collectionPoint_id', '=', $user->id);
        $total = $requests->count();

        $pending = $requests->where('status', '=', 'PENDING')->count();
        $accepted = $requests->where('status', '=', 'ACCEPTED')->count();
        $confirmed = $requests->where('status', '=', 'CONFIRMED')->count();
        $done = $requests->where('status', '=', 'DONE')->count();
        if ($total == 0) {
            return [
                "pending" => 0,
                "accepted" => 0,
                "confirmed" => 0,
                "done" => 0,
            ];    
        }
        return [
            "pending" => ($pending/$total) * 100,
            "accepted" => ($accepted/$total) * 100,
            "confirmed" => ($confirmed/$total) * 100,
            "done" => ($done/$total) * 100,
        ];
    }
}
