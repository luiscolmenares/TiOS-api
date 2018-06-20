<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventDB extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'events';
    //use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'title', 'description', 'action', 'valueFrom', 'ValueTo', 'allDay', 'start', 'end', 'url', 'className', 'editable', 'startEditable', 'durationEditable, resourceEditable', 'rendering', 'overlap', 'constraint', 'color', 'backgroundColor', 'borderColor', 'textColor', 'active', 'organization_id', 'project_id', 'datasource_id', 'datapoint_id',
    ];
}
