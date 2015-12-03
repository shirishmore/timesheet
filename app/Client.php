<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['name', 'country', 'status'];

    /**
     * Define the relation between client and Project.
     *
     * @return Eloquent\Model
     */
    public function projects()
    {
        return $this->hasMany('App\Projects');
    }
}
