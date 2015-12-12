<?php

namespace App\Http\Controllers;

use App\Client;
use App\Estimate;
use App\Http\Controllers\Controller;
use App\Project;
use App\TimeEntry;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getFilterReport(Request $request)
    {
        $timeEntryObj = new TimeEntry;
        return $timeEntryObj->getManagerTrackerReport();
    }

    public function getUserList()
    {
        return User::orderBy('name')->get();
    }

    public function getProjectList()
    {
        return Project::with('client')->with('estimates')->orderBy('name')->get();
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
        // \Log::info(print_r($data, 1));

        return $data;
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
}
