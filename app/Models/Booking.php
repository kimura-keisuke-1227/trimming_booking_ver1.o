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

    public function nonMemberBooking(){
        return $this -> hasMany('App\Models\NonMemberBooking');
    }

    public function getBookingDate(){
        return date('M月d日',$this->date);
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
                $this->pet->name.
                '('.
                $this->pet->weight.
                'kg)';
    }

    public function getBookingCourseAndDogTypeInfoForStaff(){
        return '    '.
        $this->pet->dogtype -> type .
        '('.
        $this-> course -> courseMaster -> course .
        ')';
    }

    /****************************************************************************************
    *
    *   非会員の予約に関する情報を取得
    *
    ****************************************************************************************/

    public function getNonMemberOwner(){
        $nonBookingInfo = $this -> getNonMemberBooking();
        $owner = $nonBookingInfo -> last_name;
        $owner = $owner .  $nonBookingInfo -> first_name;
        $owner = $owner . '(';
        $owner = $owner .  $nonBookingInfo -> last_name_kana;
        $owner = $owner . ' ';
        $owner = $owner .  $nonBookingInfo -> first_name_kana;
        $owner = $owner . ')';
        return $owner;
    }

    public function getPetNameOfNoMemberBooking(){
        $nonBookingInfo = $this -> getNonMemberBooking();
        return $nonBookingInfo -> name;
    }


    public function getNonMemberPetForTable(){
        $nonBookingInfo = $this -> getNonMemberBooking();
        $course_name = $this-> course -> courseMaster -> course;
        $owner_last_nm = $nonBookingInfo -> last_name;
        
        $pet = $nonBookingInfo -> name ;

        return $owner_last_nm   . $pet;
    }

    public function getDogTypeAndCourse(){
        $nonBookingInfo = $this -> getNonMemberBooking();
        $course_name = $this-> course -> courseMaster -> course;
        
        $pet = '[';
        $pet = $pet . $this->getDogTypeTest();
        $pet = $pet . ']';
        $pet = $pet . $course_name;
        return $pet;
    }

    private function getNonMemberBooking(){
        return $this ->nonMemberBooking()-> where('booking_id', $this ->id) -> first();
    }

    public function getDogType(){
        $nonBookingInfo = $this -> getNonMemberBooking();
        $dogType = $this -> getCourse();
        return  $dogType ;
    }

    private function getDogTypeTest(){
        $dogType = $this -> course -> dogtype -> type;
        return $dogType;
    }

}
