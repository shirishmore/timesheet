<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['name', 'client_id', 'status'];

    /**
     * Define relation between Project and Client.
     *
     * @return Eloquent\Model
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Define relation between Estimate and Project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estimates()
    {
        return $this->hasMany('App\Estimate');
    }

    /**
     * A project will have comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function comments()
    {
        return $this->morphToMany('App\Comment', 'commentable');
    }
}
