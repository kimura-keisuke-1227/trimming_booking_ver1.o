<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function courseMaster(){
        return $this -> belongsTo('App\Models\CourseMaster');
    }
    public function dogtype(){
        return $this -> belongsTo('App\Models\Dogtype')
        ->orderBy('order', 'asc'); // 'asc' は昇順、'desc' は降順
    }

    public function getCourseInfo(){
        $price =  $this -> price . '円';
        if($this -> price == -9999){
            $price = '金額要相談';
        }
        return $this -> courseMaster -> course . '[' . $this -> minute_for_show . '分]'; 
    }

    public function getCourseInfoForAdminBookingMaking(){
        $price =  $this -> price . '円';
        if($this -> price == -9999){
            $price = '金額要相談';
        }
        $text = $this -> dogtype -> type;
        $text = $text . ':';
        $text = $text . $this -> courseMaster -> course . '[' .' ' .$this -> minute_for_show . '分]';
        return $text;
    }

}
