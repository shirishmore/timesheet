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

            // if the user id is set, it means it's a backdate entry
            $userId = Auth::user()->id;
            $createdAt = Carbon::now();
            $updatedAt = Carbon::now();
            if ($request->input('user_id')) {
                $userId = $request->input('user_id');
                $createdAt = Carbon::parse($request->input('date'));
                $updatedAt = Carbon::parse($request->input('date'));
            }

            $entryId = DB::table('time_entries')->insertGetId([
                'desc' => $request->input('desc'),
                'user_id' => $userId,
                'project_id' => $project->id,
                'project_name' => $project->name,
                'client_name' => $project->client->name,
                'time' => $request->input('time'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            // adding the entry of the ticket with tags mapping table
            foreach ($request->input('tags') as $key => $value) {
                DB::table('taggables')->insert([
                    'tag_id' => $value,
                    'taggable_id' => $entryId,
                    'taggable_type' => 'ticket',
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);
            }

            if ($request->input('estimate_id') && $request->input('estimate_id') != 0) {
                DB::table('time_entry_estimates')->insert([
                    'time_entry_id' => $entryId,
                    'estimate_id' => $request->input('estimate_id'),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
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

        DB::table('taggables')
            ->where('taggable_id', $entry->id)
            ->where('taggable_type', 'ticket')
            ->delete();
    }

    public function backdateTimeEntry($otp, $userId)
    {
        $projects = Project::with('client')->get();
        $tags = Tag::all();

        $otp = DB::table('backdate_timeentry')
            ->where('otp', $otp)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->first();

        if (!$otp) {
            abort(403, 'Wrong url');
        }

        return view('manager.backdate-form', compact('projects', 'tags', 'otp'));
    }
}
