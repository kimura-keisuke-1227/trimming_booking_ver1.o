<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultCapacity extends Model
{
    use HasFactory;

    public function salon(){
        return $this -> belongsTo('App\Models\Salon');
    }
}
