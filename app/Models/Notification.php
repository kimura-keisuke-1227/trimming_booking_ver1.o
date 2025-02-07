<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function notificationSetting(){
        $this->belongsTo('App\Models\NotificationSetting');
    }
    protected $fillable = ['contents', 'st_date', 'ed_date']; 
}

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Notification extends Model
// {
//     use HasFactory;
   
// }

