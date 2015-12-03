<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Project;
use App\Tag;
use App\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TrackerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listing all the entries of the user
     *
     * @return Illuminate\Http\Response
     */
    public function listEntries()
    {
        $projects = Project::with('client')->get();

        $tags = Tag::all();

        $trackers = TimeEntry::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $dataArr = [];
        foreach ($trackers as $tracker) {
            $dataArr[$tracker->created_at]['entries'][] = $tracker;
            // $dataArr[$tracker->created_at]['timeE'][] = (float) $tracker->time;

            if (!isset($dataArr[$tracker->created_at]['time'])) {
                $dataArr[$tracker->created_at]['time'] = (float) $tracker->time;
            } else {
                $dataArr[$tracker->created_at]['time'] = $dataArr[$tracker->created_at]['time'] + (float) $tracker->time;
            }
        }

        return view('tracker.tracker-listing', compact('dataArr', 'trackers', 'projects', 'tags'));
    }

    /**
     * For to add a new entry.
     */
    public function addTracker()
    {
        $projects = Project::with('client')->get();
        $tags = Tag::all();

        return view('tracker.tracker-add', compact('projects', 'tags'));
    }

    public function saveTrackerEntry(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'desc' => 'required|min:5',
            'time' => 'required|numeric',
            'tags' => 'required',
            'project_id' => 'required',
        ]);

        if ($request->input('project_id') == 'Select Project') {
            Session::flash('flash_error', 'You need to select the project');
            return redirect()->back()->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $project = Project::with('client')->find($request->input('project_id'));

            $entry = TimeEntry::create([
                'desc' => $request->input('desc'),
                'user_id' => Auth::user()->id,
                'project_id' => $project->id,
                'project_name' => $project->name,
                'client_name' => $project->client->name,
                'time' => $request->input('time'),
            ]);

            // adding the entry of the ticket with tags mapping table
            foreach ($request->input('tags') as $key => $value) {
                DB::table('taggables')->insert([
                    'tag_id' => $value,
                    'taggable_id' => $entry->id,
                    'taggable_type' => 'ticket',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            if ($request->input('estimate_id')) {
                DB::table('time_entry_estimates')->insert([
                    'time_entry_id' => $entry->id,
                    'estimate_id' => $request->input('estimate_id'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::update("UPDATE estimates SET hours_consumed = hours_consumed + :hours WHERE id = :id", [
                    'hours' => $request->input('time'),
                    'id' => $request->input('estimate_id'),
                ]);
            }

            DB::commit();

            Session::flash('flash_message', 'Entry saved');

            return redirect('time-tracker');
        } catch (\PDOException $e) {
            DB::rollBack();
            abort(403, 'Data was not saved. Try again' . $e->getMessage());
        }
    }

    public function deleteTrackerEntry(Request $request)
    {
        $trackerId = $request->input('trackerId');

        $entry = TimeEntry::findOrFail($trackerId);

        $estimateRecord = DB::table('time_entry_estimates')->where('time_entry_id', $entry->id)->first();

        if (count($estimateRecord) > 0) {
            $estId = $estimateRecord->estimate_id;

            DB::update("UPDATE estimates SET hours_consumed = hours_consumed - :hours WHERE id = :id", [
                'hours' => $entry->time,
                'id' => $estId,
            ]);
        }

        $entry->delete();

        DB::table('time_entry_estimates')->where('time_entry_id', $entry->id)->delete();
    }
}
