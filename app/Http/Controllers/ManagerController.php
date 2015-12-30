<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PieGraph;
use App\TimeEntry;
use App\User;
use Carbon\Carbon;
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

        if (true != $flag) {
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
                    'date' => Carbon::parse($entry->created_at)->toDateString(),
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
    //param 1 : $sdate = 'yyyy-mm-dd'
    //param 2 : $edate = 'yyyy-mm-dd'
    public function downloadProjectWiseReport($sdate, $edate)
    {
        $timeEntryObj = new TimeEntry;
        $timeEntries = $timeEntryObj->getProjectWiseReport($sdate, $edate);

        Excel::create('Timesheet_ProjectWise_Report_' . time(), function ($excel) use ($timeEntries) {

            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Date' => $entry->createdDate,
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

    public function downloadProjectWiseDetailedReport($sdate, $edate)
    {
        $timeEntryObj = new TimeEntry;
        $timeEntries = $timeEntryObj->getProjectWiseDetailedReport($sdate, $edate);

        Excel::create('Timesheet_ProjectWise_Detailed_Report_' . time(), function ($excel) use ($timeEntries) {
            //echo "ss<pre>";print_r($timeEntries);die;
            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Date' => $entry->createdDate,
                    'Project Name' => $entry->projectName,
                    'Client Name' => $entry->clientName,
                    'Time' => $entry->time,
                    'Total Time' => $entry->totalTime,
                    'Team' => $entry->team,
                ];
            } //echo "ss<pre>";print_r(count($data));die;

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $last_row = count($data) + 1;
                $sheet->fromArray($data);
                foreach ($data as $k => $v) {
                    $row_num = $k + 2;
                    if (empty($v['Time'])) {
                        $sheet->row($row_num, function ($row) {
                            // call cell manipulation methods
                            $row->setBackground('#FFFF00');
                        });
                        $sheet->row($row_num, array($v['Date'], $v['Project Name'] . ' Total', $v['Client Name'], $v['Time'], $v['Total Time'], $v['Team']));
                    }
                    $sheet->row($last_row, array('', 'Total', '', '', $v['Total Time'], ''));
                    //Last row: total of all projects
                    $sheet->row($last_row, function ($row) {
                        // call cell manipulation methods
                        $row->setBackground('#0000FF');
                    });
                }
            });
        })->download('xls');
    }

    public function downloadDateWiseReport($sdate, $edate)
    {
        $timeEntryObj = new TimeEntry;
        $timeEntries = $timeEntryObj->getDateWiseReport($sdate, $edate);
        Excel::create('Timesheet_DateWise_Report_' . time(), function ($excel) use ($timeEntries) {

            $data = [];
            foreach ($timeEntries as $entry) {
                $data[] = [
                    'Date' => $entry->createdDate,
                    'Task' => $entry->description,
                    'Project Name' => $entry->projectName,
                    'Client Name' => $entry->clientName,
                    'Tags' => $entry->tags,
                    'Duration' => $entry->time,
                    'Team' => $entry->username,
                ];
            }

            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }

    public function createPieChart($sdate, $edate)
    {
        $timeEntryObj = new TimeEntry;
        $timeEntries = $timeEntryObj->getProjectWiseReport($sdate, $edate);

        $data = [];
        $cnt = 0;
        $pie = new PieGraph();
        foreach ($timeEntries as $entry) {
            $time_arr[] = $entry->totalTime;
            $pname_arr[] = $entry->projectName;
            $color_arr[] = $pie->random_color();
        }
        $pie->setImage(200, 100, $time_arr);

        // colors for the data
        //$color_arr = ["#ff0000", "#ff8800", "#0022ff", "#989898", "#6600CC", "#FF0000 ", "#660066", "#CCFF00", "#FF0099", "#33ff99", "#33ff11"];
        $pie->setColors($color_arr);

        // legends for the data
        $pie->setLegends($pname_arr);

        // Display creation time of the graph
        $pie->DisplayCreationTime();

        // Height of the pie 3d effect
        $pie->set3dHeight(15);

        // Display the graph
        $pie->display();
    }
}
