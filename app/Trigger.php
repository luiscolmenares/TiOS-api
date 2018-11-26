<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trigger extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'triggers';
    // use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'operator', 'value' , 'trigger_action_type_id', 'project_id', 'datasource_id', 'datapoint_id', 'active', 'notes', 'recipients', 'custommessage', 'act_datasource_id', 'act_datapoint_id', 'act_new_value',
    ];
}

