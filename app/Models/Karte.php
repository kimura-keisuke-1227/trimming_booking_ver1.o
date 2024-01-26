<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karte extends Model
{
    use HasFactory;

    protected $fillable=['date','pet_id','karte_for_staff','karte_for_owner'];

    public function pet(){
        return $this -> belongsTo('App\Models\Pet' );
    }
}
