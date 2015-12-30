<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Backdate_Timeentry extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backdate_timeentry';
    /**
     * Define the fillable columns.
     *
     * @var array
     */
    protected $fillable = [ 'user_id', 'otp', 'backdate', 'status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getLatestBackdateTimeEntries()
    {
        $select = [
            'bt.id as id',
            'bt.user_id as user_id',
            'bt.backdate as backdate',
            'bt.status as status',
            'u.name as name'
        ];
        return DB::table('backdate_timeentry as bt')
            ->select($select)
            ->join('users as u', 'u.id', '=', 'bt.user_id','left')
            ->orderBy('bt.id', 'desc')
            ->get();
    }

    public function getBackDateEntryById($id)
    {
        //return Project::with('backdate_requests')->with('users')->orderBy('name')->get();
        //return DB::table('backdate_timeentry')->where('id','=',$id)->get();
        $select = [
            'bt.id as id',
            'bt.user_id as user_id',
            'bt.backdate as backdate',
            'bt.status as status',
            'u.name as name'
        ];
        return DB::table('backdate_timeentry as bt')
            ->select($select)
            ->join('users as u', 'u.id', '=', 'bt.user_id')
            ->orderBy('bt.id', 'desc')
            ->where('bt.id','=',$id)
            ->get();
    }
}
