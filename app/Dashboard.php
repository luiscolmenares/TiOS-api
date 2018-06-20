<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'dashboards';
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'options', 'project_id', 'active',
    ];
    
    public function panels()
    {
        return $this->hasMany('App\Panel');
    }
}
