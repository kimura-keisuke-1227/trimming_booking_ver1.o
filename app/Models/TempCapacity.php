<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\classes\Util;

class TempCapacity extends Model
{
    use HasFactory;

    public function getStTime(){
        return Util::minuteToTime($this -> st_time);
    }
    public function getEdTime(){
        return Util::minuteToTime($this -> ed_time);
    }

    public function salon(){
        return $this -> belongsTo('App\Models\Salon');
    }
}
