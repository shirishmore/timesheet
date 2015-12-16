<?php

namespace App\Http\Controllers;

use App\Client;
use App\Comment;
use App\Estimate;
use App\Project;
use App\TimeEntry;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function getFilterReport(Request $request)
    {
        $timeEntryObj = new TimeEntry;
        return $timeEntryObj->getManagerTrackerReport();
    }

    /**
     * Get the list of users in the system
     *
     * @return mixed
     */
    public function getUserList()
    {
        return User::orderBy('name')->get();
    }

    /**
     * Get the list of projects in the system with client and estimate data
     *
     * @return mixed
     */
    public function getProjectList()
    {
        return Project::with('client')->with('estimates')->orderBy('name')->get();
    }

    /**
     * Get all the comments for a project
     *
     * @param $projectId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getProjectComments($projectId)
    {
        $select = [
            'c.comment as comment',
            'u.name as name',
            'c.created_at as created',
            'p.name as project',
            'p.id as project_id',
        ];
        $query = DB::table('comments as c');
        $query->select($select);
        $query->join('commentables as ct', 'c.id', '=', 'ct.comment_id', 'left');
        $query->join('projects as p', 'p.id', '=', 'ct.commentable_id');
        $query->join('users as u', 'u.id', '=', 'c.user_id');
        $query->where('ct.commentable_id', $projectId);

        $result = $query->get();
        return $result;
    }

    public function saveProjectComment(Request $request)
    {
        $comment = new Comment([
            'user_id' => Auth::user()->id,
            'comment' => $request->input('comment'),
            'parent_id' => 0,
            'thread' => '',
            'status' => 1,
        ]);

        $project = \App\Project::find($request->input('project_id'));
        $project->comments()->save($comment);

        $result = $this->getProjectComments($request->input('project_id'));
        return $result;
    }

    public function deleteProjectById(Request $request)
    {
        $project = Project::findOrFail($request->input('id'));
        $project->delete();

        return response('Project deleted', 200);
    }

    public function getClientList()
    {
        return Client::orderBy('name')->get();
    }

    public function getProjectById($id)
    {
        return Project::where('id', $id)->with('client')->with('estimates')->first();
    }

    public function getEstimateById($id)
    {
        return Estimate::findOrFail($id);
    }

    public function updateEstimateById(Request $request)
    {
        $estimate = Estimate::find($request->input('id'));
        $estimate->desc = $request->input('desc');
        $estimate->hours_allocated = $request->input('hours_allocated');
        $estimate->status = $request->input('status');
        $estimate->save();

        return response($estimate, 200);
    }

    public function getFilterReportSearch(Request $request)
    {
        $query = DB::table('time_entries as te');
        $query->select(['te.desc as desc', 'te.time as time', 'u.name as username', 'te.project_name as project_name', 'te.client_name as client_name', DB::raw("DATE(te.created_at) as createdDate")]);

        $query->join('users as u', 'u.id', '=', 'te.user_id', 'left');

        if ($request->input('desc')) {
            $desc = $request->input('desc');
            $query->where('te.desc', 'like', "%{$desc}%");
        }

        if ($request->input('users')) {
            if (count($request->input('users')) == 1) {
                $query->where('te.user_id', $request->input('users')[0]);
            } else {
                foreach ($request->input('users') as $userId) {
                    $query->orWhere('te.user_id', $userId);
                }
            }
        }

        if ($request->input('projects')) {
            if (count($request->input('projects')) == 1) {
                $query->where('te.project_id', $request->input('projects')[0]);
            } else {
                foreach ($request->input('projects') as $projectId) {
                    $query->orWhere('te.project_id', $projectId);
                }
            }
        }

        if ($request->input('startDate')) {
            $startDate = Carbon::parse($request->input('startDate'));
            $string = $startDate->year . '-' . $startDate->month . '-' . $startDate->day . ' 00:00:00';
            $query->where('te.created_at', '>=', $string);
        }

        if ($request->input('endDate')) {
            $endDate = Carbon::parse($request->input('endDate'));
            $stringEndDate = $endDate->year . '-' . $endDate->month . '-' . ($endDate->day + 1) . ' 00:00:00';
            $query->where('te.created_at', '<=', $stringEndDate);
        }

        $query->orderBy('te.created_at', 'desc');

        $result = $query->get();

        $finalData = [];
        foreach ($result as $row) {
            $finalData[] = [
                'description' => $row->desc,
                'time' => $row->time,
                'username' => $row->username,
                'projectName' => $row->project_name,
                'clientName' => $row->client_name,
                'createdDate' => $row->createdDate,
            ];
        }

        return response($finalData, 200);
    }

    public function getTimeSheetEntryByDate()
    {
        $dt = Carbon::now()->subDays(7);
        $dateString = $dt->year . '-' . $dt->month . '-' . $dt->day . ' 00:00:00';
        $query = DB::table('time_entries as te');
        $query->select(["te.*", DB::raw("SUM(te.time) AS totalTime")]);
        $query->groupBy('te.project_name');
        $query->groupBy(DB::raw("DATE(te.created_at)"));
        $query->where('te.created_at', '>', $dateString);
        $query->orderBy('te.created_at', 'desc');
        $result = $query->get();

        $data = [];
        foreach ($result as $row) {
            $dt = Carbon::parse($row->created_at);
            $date = $dt->format('D dS, M y');
            $data[$date]['labels'][] = "{$row->project_name} ({$row->client_name})";
            $data[$date]['data'][] = $row->totalTime;
        }

        $data = array_slice($data, 0, 3);

        return $data;
    }

    public function getTimeEntryForEstimate($id)
    {
        $query = DB::table('time_entry_estimates as tee');
        $query->where('tee.estimate_id', $id);
        $query->join('time_entries as te', 'te.id', '=', 'tee.time_entry_id', 'left');
        $query->join('users as u', 'u.id', '=', 'te.user_id', 'left');
        $query->orderBy('te.created_at', 'desc');
        return $query->get();
    }

    public function saveProjectEstimate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer',
            'desc' => 'required|min:5',
            'hours_allocated' => 'required',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 301);
        }

        $estimate = Estimate::create([
            'desc' => $request->input('desc'),
            'project_id' => $request->input('project_id'),
            'hours_allocated' => $request->input('hours_allocated'),
            'hours_consumed' => 0,
            'status' => "In progress",
        ]);

        return response($estimate, 201);
    }

    public function saveNewProject(Request $request)
    {
        if (Gate::denies('addClient', new Client)) {
            abort(403, 'You are now allowed here');
        }

        $rules = ['name' => 'required', 'client' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $project = new Project;
        $project->name = $request->input('name');
        $project->client_id = $request->input('client');
        $project->status = 'active';
        $project->save();

        return response($project, 201);
    }

    public function allowBackdateEntry(Request $request)
    {
        // return $request->all();
        $date = Carbon::parse($request->input('date'));
        $userIds = $request->input('users');

        $data = [];
        foreach ($userIds as $id) {
            $data[] = [
                'user_id' => $id,
                'backdate' => $date,
                'otp' => uniqid(),
            ];
        }

        DB::table('backdate_timeentry')->insert($data);
    }
}
