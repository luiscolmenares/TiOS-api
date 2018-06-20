<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    /**
     * The adatabase table use by the model.
     *
     * @var string
     */
    protected $table = 'organizations';
    use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'address2', 'phone', 'notes', 'active',
    ];
    protected $dates = ['deleted_at'];

    public function Users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
    public function Projects()
    {
        return $this->belongsToMany('App\Project')->withTimestamps();
    }
}

