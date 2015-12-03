<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Input;
use Session;
use Validator;
use View;

class RoleController extends Controller
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
        $role = Role::all();
        return view('role.index', compact('role'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::lists('name', 'id');
        return View::make('role.create')->with('role', $role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        $rules = ['name' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('role/create')->withErrors($validator)->withInput();
        }

        // store
        $role = new Role;
        $role->name = $request->input('name');
        $role->status = 'status';
        $role->save();

        /*$msg = 'Role Successfully created.';
        return Response::json(array('message' => $msg ,'data'=> [
        'reg' => $role,
        'type' => 'save'
        ]), 200);*/
        // redirect
        Session::flash('message', 'Role Successfully created !');
        return redirect('role');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        // show the view and pass the role to it
        return View::make('role.show')
            ->with('role', $role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
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
        $rules = ['name' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('role/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        // store
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        Session::flash('message', 'Role Successfully updated!');
        return redirect('role');
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
        $role = Role::findOrFail($id);
        $role->delete();

        /*$msg = 'Role Successfully deleted.';
        return Response::json(array('message' => $msg ,'data'=> [
        'reg' => $role,
        'type' => 'save'
        ]), 200); */
        // redirect
        Session::flash('message', 'Role successfully deleted!');
        return Redirect::to('role');
    }
}
