<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Project;
use App\TimeEntry;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return Project::with('client')->with('estimates')->get();
    }

    public function getProjectById($id)
    {
        return Project::where('id', $id)->with('client')->with('estimates')->first();
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
            \Log::info($string);
            $query->where('te.created_at', '>=', $string);
        }

        if ($request->input('endDate')) {
            $endDate = Carbon::parse($request->input('endDate'));
            $stringEndDate = $endDate->year . '-' . $endDate->month . '-' . ($endDate->day + 1) . ' 00:00:00';
            \Log::info($stringEndDate);
            $query->where('te.created_at', '<=', $stringEndDate);
        }

        $query->orderBy('te.created_at', 'desc');

        $result = $query->get();
        // \Log::info($query->toSql());

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
}
