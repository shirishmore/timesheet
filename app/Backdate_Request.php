<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Backdate_Request extends Model
{
    /**
     * Define the fillable columns.
     *
     * @var array
     */
    protected $fillable = [ 'user_id','project_manager_id', 'otp', 'backdate', 'status'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backdate_requests';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getLatestRequestBackdateTimeEntries()
    {
        $select = [
            'br.id as id',
            'br.user_id as user_id',
            'br.project_manager_id as project_manager_id',
            'br.backdate as backdate',
            'br.status as status',
            'u.name as name'
        ];
        $query = DB::table('backdate_requests as br')
            ->select($select)
            ->join('users as u', 'u.id', '=', 'br.project_manager_id','left')
            ->orderBy('br.id', 'desc')
            ->get();
        return $query;
    }

    public function getRequestBackDateEntryById($id)
    {
        //return Project::with('backdate_requests')->with('users')->orderBy('name')->get();
        return DB::table('backdate_requests')->where('id','=',$id)->get();
    }

}
