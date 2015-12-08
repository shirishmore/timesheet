<?php

namespace App\Http\Controllers;

use App\Client;
use App\Estimate;
use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Input;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::with('client')->get();
        return view('project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('addClient', new Client)) {
            abort(403, 'You are now allowed here');
        }

        $client = Client::lists('name', 'id');
        return view('project.create')->with('client', $client);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('addClient', new Client)) {
            abort(403, 'You are now allowed here');
        }

        // validate
        $rules = ['name' => 'required', 'client' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('project/create')->withErrors($validator)->withInput();
        }
        // store
        $project = new Project;
        $project->name = $request->input('name');
        $project->client_id = $request->input('client');
        $project->status = 'active';
        $project->save();

        Session::flash('message', 'Project Successfully created !');
        return redirect('project');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estimates = Estimate::where('project_id', $id)->with('project')->orderBy('created_at', 'desc')->paginate(20);

        $project = Project::with('client')->with('estimates')->find($id);

        return view('project.show', compact('project', 'estimates'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = ['name' => 'required', 'client' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('project/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        // store
        $project = Project::findOrFail($id);
        $project->name = $request->input('name');
        $project->country = $request->input('client');
        $project->save();

        Session::flash('message', 'Project Successfully updated!');
        return redirect('project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $project = Project::findOrFail($id);
        $project->delete();

        Session::flash('message', 'Project successfully deleted!');
        return Redirect::to('project');
    }

    /**
     * Add Estimate form and the listing is shown here.
     *
     * @param project id
     */
    public function addEstimate($id)
    {
        if (Gate::denies('addProjectEstimate', new Estimate)) {
            abort(403, 'You are not allowed here');
        }

        $project = Project::findOrFail($id);

        $estimates = Estimate::where('project_id', $id)->with('project')->orderBy('created_at', 'desc')->paginate();

        return view('project.project-estimate-add', compact('project', 'id', 'estimates'));
    }

    /**
     * Saving the estimate from the project page.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function saveEstimate(Request $request)
    {
        if (Gate::denies('addProjectEstimate', new Estimate)) {
            abort(403, 'You are not allowed here');
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer',
            'desc' => 'required|min:5',
            'hours_allocated' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $estimate = Estimate::create([
            'desc' => $request->input('desc'),
            'project_id' => $request->input('project_id'),
            'hours_allocated' => $request->input('hours_allocated'),
            'hours_consumed' => 0,
            'status' => "In progress",
        ]);

        return redirect()->back();
    }

    public function getProjectEstimates(Request $request)
    {
        $id = $request->input('project_id');

        $estimates = Estimate::where('project_id', $id)
            ->where('status', '!=', 'Completed')
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($estimates) == 0) {
            return '<h3>No Estimates for this Project</h3>';
        }

        $output = '<label>Estimate</label><select name="estimate_id" class="form-control">';
        $output .= "<option value='0'>NONE</option>";

        foreach ($estimates as $estimate) {
            $output .= "<option value='{$estimate->id}'>{$estimate->desc}</option>";
        }

        $output .= '</select>';

        return $output;
    }

    public function editEstimate($id)
    {
        if (Gate::denies('addProjectEstimate', new Estimate)) {
            abort(403, 'You are not allowed here');
        }

        $estimate = Estimate::with('project')->findOrFail($id);
        return view('project.edit-estimate', compact('estimate'));
    }

    public function updateEstimate(Request $request)
    {
        if (Gate::denies('addProjectEstimate', new Estimate)) {
            abort(403, 'You are not allowed here');
        }

        $estimate = Estimate::findOrFail($request->input('project_id'));
        $estimate->desc = $request->input('desc');
        $estimate->hours_allocated = $request->input('hours_allocated');
        $estimate->status = $request->input('status');
        $estimate->save();

        Session::flash('flash_message', 'Estimate updated.');
        return redirect('project/estimates/' . $estimate->project->id);
    }
}
