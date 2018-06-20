<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Zizaco\Entrust\EntrustRole;

class Role extends Model
{
     /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'roles';
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'display_name', 'description',
    ];
}
