<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\classes\Util;

class Booking extends Model
{
    use HasFactory;

    public function pet(){
        return $this -> belongsTo('App\Models\Pet');
    }
    
    public function course(){
        return $this -> belongsTo('App\Models\Course');
    }
    public function salon(){
        return $this -> belongsTo('App\Models\Salon');
    }


    public function getStartTime(){
        $timeNum = $this -> st_time;
        return Util::minuteToTime($timeNum);
    }

    public function getEndTime(){
        $timeNum = $this -> ed_time;
        return Util::minuteToTime($timeNum);
    }

    public function getCourse(){
        return $this -> course -> courseMaster -> course;
    }

    public function getBookingInfo(){
        return $this-> pet -> name . '('.$this-> course -> courseMaster -> course . ')';
    }

    public function getBookingInfoForStaff(){
        return  $this->pet->user->last_name .
                $this->pet->name
                . '('.$this-> course -> courseMaster -> course . ')';
    }

}
