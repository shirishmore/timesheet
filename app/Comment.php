<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Define the fillable columns
     *
     * @var array
     */
    protected $fillable = ['user_id', 'comment', 'parent_id', 'thread', 'status'];

    /**
     * How project is related to a comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function project()
    {
        return $this->morphedByMany('App\Project', 'commentable');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
