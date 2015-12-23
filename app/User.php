<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract,
AuthorizableContract,
CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'dob', 'employee_id', 'joining_date'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function roles()
    {
        return DB::table('roles_users as ur')
            ->select('r.name as role')
            ->join('roles as r', 'r.id', '=', 'ur.role_id')
            ->join('users as u', 'u.id', '=', 'ur.user_id')
            ->where('u.id', Auth::user()->id)->get();
    }

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function getUserListByRole($roleIds)
    {
        $roles = implode(",", $roleIds);
        $select = [
            'u.name as name',
            'u.id as id',
        ];
        $query = DB::table('users as u');
        $query->select($select);
        $query->join('roles_users as ru', 'u.id', '=', 'ru.user_id');
        $query->join('roles as r', 'r.id', '=', 'ru.role_id');
        $query->whereRaw('ru.role_id IN ('.$roles.')');
        $result = $query->get();
        return $result;
    }
}
