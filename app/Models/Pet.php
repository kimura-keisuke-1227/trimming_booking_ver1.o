<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
