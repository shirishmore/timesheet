<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Input;
use Session;
use Validator;
use View;

class ClientController extends Controller
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
        if (Gate::denies('viewClients', new Client)) {
            abort(403, 'Not allowed');
        }

        $clients = Client::all();
        return View::make('client.index')->with('clients', $clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('addClient', new Client)) {
            abort(403, 'Not allowed');
        }

        return View::make('client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::to('clients/create')->withErrors($validator)->withInput();
        }

        // store
        $client = new Client;
        $client->name = $request->input('name');
        $client->country = $request->input('country');
        $client->status = 'active';

        $client->save();

        Session::flash('message', 'Client Successfully created!');
        return redirect('clients');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);

        // show the view and pass the client to it
        return View::make('client.show')
            ->with('client', $client);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('client.edit', compact('client'));
        //return View::make('client.edit')
          //  ->with('client', $client);
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
        $rules = ['name' => 'required', 'country' => 'required'];

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('clients/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        // store
        $client = Client::findOrFail($id);
        $client->name = $request->input('name');
        $client->country = $request->input('country');
        $client->save();

        Session::flash('message', 'Client Successfully updated!');
        return redirect('clients');
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
        $client = Client::findOrFail($id);
        $client->delete();

        Session::flash('message', 'Client successfully deleted!');
        return Redirect::to('clients');
    }
}
