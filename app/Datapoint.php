<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Datapoint extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'datapoints';
    use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type' , 'unitid', 'address', 'data', 'options', 'active', 'datasource_id',
    ];
    
}
