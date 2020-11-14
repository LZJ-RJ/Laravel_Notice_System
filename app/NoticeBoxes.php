<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoticeBoxes extends Model
{

    protected $fillable = [
        'owner',
        'box_source_event',
        'box_type',
        'box_content',
        'read_at',
    ];

}
