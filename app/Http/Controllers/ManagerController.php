<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\TimeEntry;
use App\User;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
    public function __construct()
    {
        $allowed = ['Admin', 'Project Manager'];
        $userRoles = User::roles();
        $flag = false;

        foreach ($userRoles as $role) {
            if (in_array($role->role, $allowed)) {
                $flag = true;
            }
        }

        if ($flag != true) {
            abort(403, 'Now allowed');
        }
    }

    public function getTimeReport()
    {
        return view('manager.report-main');
    }

    public function downloadReport()
    {
        Excel::create('Timesheet_Report_' . time(), function ($excel) {
            $timeEntryObj = new TimeEntry;
            $timeEntries = $timeEntryObj->getManagerTrackerReport();

            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'description' => $entry->description,
                    'time' => $entry->time,
                    'username' => $entry->username,
                    'projectName' => $entry->projectName,
                    'clientName' => $entry->clientName,
                    'tags' => $entry->tags,
                ];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }

    public function downloadProjectWiseReport()
    {
        Excel::create('Timesheet_ProjectWise_Report_' . time(), function ($excel) {
            $timeEntryObj = new TimeEntry;
            $timeEntries = $timeEntryObj->getProjectWiseReport();

            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Project Name' => $entry->projectName,
                    'Client Name' => $entry->clientName,
                    'Total Time' => $entry->totalTime,
                    'Team' => $entry->team,
                ];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }

    public function downloadProjectWiseDetailedReport()
    {
        Excel::create('Timesheet_ProjectWise_Detailed_Report_' . time(), function ($excel) {
            $timeEntryObj = new TimeEntry;
            $timeEntries = $timeEntryObj->getProjectWiseDetailedReport();
            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Project Name' => $entry->projectName,
                    'Client Name' => $entry->clientName,
                    'Total Time' => $entry->totalTime,
                    'Team' => $entry->team,
                ];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }

    public function downloadDateWiseReport()
    {
        Excel::create('Timesheet_DateWise_Report_' . time(), function ($excel) {
            $timeEntryObj = new TimeEntry;
            $timeEntries = $timeEntryObj->getDateWiseReport();

            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Project Name' => $entry->projectName,
                    'Client Name' => $entry->clientName,
                    'Total Time' => $entry->totalTime,

                ];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }
}
