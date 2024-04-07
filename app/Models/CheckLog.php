<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckLog extends Model
{
    use HasFactory;
    const STR_TABLE_NAME_OF_CHECK_LOGS = 'check_logs';

    const STR_COLUMN_NAME_OF_USER_INFO = 'user_info';
    const STR_COLUMN_NAME_OF_SUMMARY = 'summary';
    const STR_COLUMN_NAME_OF_DETAIL = 'detail';
    const STR_COLUMN_NAME_OF_REQUEST = 'request';

    protected $fillable = [
        'user_info','summary','detail','request'
    ];
}
