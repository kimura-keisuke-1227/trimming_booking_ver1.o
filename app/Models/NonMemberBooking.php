<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonMemberBooking extends Model
{
    use HasFactory;

    public function booking(){
        return $this -> belongsTo('App\Models\Booking');
    }

    public function dogtype(){
        return $this -> belongsTo('App\Models\Dogtype');
    }

    public function getBookingInfo(){
        return 'hoge';
    }

    private function getBooking(){
        return $this-> where('booking_id', 1) -> first();
    }
}
