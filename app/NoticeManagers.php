<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoticeManagers extends Model
{

    protected $fillable = [
        'event',
        'target',
        'email_subject',
        'email_content',
        'email_activated',
        'sms_content',
        'sms_activated',
    ];

}
