<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    const CONST_STR_TABLE_NAME_OF_HOLIDAYS = 'holidays';

    const CONST_STR_COLUMN_NAME_OF_SALON_ID = 'salon_id';
    const CONST_STR_COLUMN_NAME_OF_DATE = 'date';

    public function salon(){
        return $this -> belongsTo('App\Models\salon');
    }
    use HasFactory;
}
