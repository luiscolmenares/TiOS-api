<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Datasource extends Model
{
    /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'datasources';
    use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'unitid', 'ip', 'port', 'options', 'data', 'notes', 'active', 'project_id', 'space_id', 'type_codename',
    ];
    
    public function Datapoints()
    {
        return $this->belongsToMany('App\Datapoint')->withTimestamps();
    }
}