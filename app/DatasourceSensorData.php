<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasourceSensorData extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'datasource_sensor_datas';
    // use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id', 'project_id', 'datasource_id', 'topic', 'value', 'timestamp', 'space_id'
    ];
}
