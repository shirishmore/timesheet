<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    /**
     * Define the columns which can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'desc', 'hours_allocated', 'hours_consumed', 'status'];

    /**
     * Define the relation between estimate and project.
     *
     * @return Eloquent relation
     */
    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
