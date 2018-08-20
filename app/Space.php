<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
      /**
     * The database table use by the model.
     *
     * @var string
     */
    protected $table = 'spaces';
    // use SoftDeletes;
    //added by me following the tutorial
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image', 'organization_id', 'project_id', 'icon_image',
    ];
}
