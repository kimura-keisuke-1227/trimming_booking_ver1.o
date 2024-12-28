<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\classes\Util;

use Illuminate\Support\Facades\Log;

class Pet extends Model
{
    use HasFactory;

    public function user(){
        return $this -> belongsTo('App\Models\User' ,'owner_id');
    }
    public function dogtype(){
        return $this -> belongsTo('App\Models\Dogtype');
    }

    public function bookings(){
        return $this -> hasMany('App\Models\Bookings');
    }

    public function getData(){
        return $this -> name . '('.$this -> dogtype -> type .' )';
    }

    public function getDataWithOwner(){
        return $this -> user -> getUserInfo() . ' ー ' . $this -> getData();
    } 

    public function getNameAndWeightForTimeTable(){
        return $this -> user -> last_name . $this-> name ;
    }

    public function getPetBirthday(){
        return Util::dbDateToStrDateForBirthday($this->birthday);
    }

    public function getPetInfoForBookingListMobile(){
        $getPetInfoForBookingListMobile = $this -> name;
    }

    public function getAgeOfPet(){
        $birthday = $this -> birthday;
        if(is_null($birthday)){
            return '誕生日未登録';
        }

        

        return '誕生日あり';

    }

    public function pets(){
        return $this-> hasMany('App\Models\L\Karte');
    }

    public function getPetInfoForAdminMobile(){
        return  
        $this -> user->last_name .
        $this->name .
        '('.
        $this->dogtype -> type.
        ' / '.
        $this->weight.
        'kg / '.
        Util::getAge(date('Y-m-d'),$this -> birthday) .
        ')'
        ;
    }

    public function getAge($date){
        Log::debug(__METHOD__ . '(' . __LINE__ . ') date:' . $date);
        return Util::getAge($date,$this -> birthday);
    }

    public function owner(){
        return $this -> user -> last_name . $this -> user -> first_name ;
    }
}
