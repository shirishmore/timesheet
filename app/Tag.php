<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function timeentries()
    {
        return $this->morphedByMany('App\TimeEntry', 'taggable');
    }
}
