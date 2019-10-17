<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'projects';
    use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'notes', 'active', 'organization_id', 'address_1', 'address_2', 'city', 'state', 'zip', 'photo', 'website', 'image',
    ];
    protected $dates = ['deleted_at'];
    public function Users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    public function DataSources()
    {
        return $this->hasMany('App\Datasource');
    }
}
