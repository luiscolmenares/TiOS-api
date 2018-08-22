<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileNotification extends Model
{
    /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'mobile_notifications';
    // use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'space', 'topic', 'value', 'project_id', 'timestamp'
    ];
}
