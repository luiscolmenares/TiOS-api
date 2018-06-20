<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'panels';
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'order', 'type', 'data', 'options', 'active', 'dashboard_id', 'datapoint_id',  'datasource_id',
    ];
    
    public function panels()
    {
        return $this->belongsTo('App\Dashboard');
    }
    
}
