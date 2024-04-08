<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;
    const STR_TABLE_NAME_OF_CHECK_LOGS = 'access_logs';

    const STR_COLUMN_NAME_OF_USER_INFO = 'user_info';
    const STR_COLUMN_NAME_OF_METHOD = 'method';
    const STR_COLUMN_NAME_OF_USER_ID = 'user_id';
    const STR_COLUMN_NAME_OF_SUMMARY = 'summary';
    const STR_COLUMN_NAME_OF_DETAIL = 'detail';
    const STR_COLUMN_NAME_OF_REQUEST = 'request';

    const INT_LENGTH_OF_SUMMARY = 255;
    const INT_LENGTH_OF_USER_INFO = 255;
    const INT_LENGTH_OF_USER_METHOD = 255;

    protected $fillable = [
        'user_info','summary','detail','request',
        'user_id','method'
    ];
}
