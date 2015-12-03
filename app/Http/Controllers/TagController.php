<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tags = Tag::orderBy('name', 'asc')->get();

        return view('tags.tag-listing', compact('tags'));
    }

    public function create(Request $request)
    {
        if (Gate::denies('add', new Tag)) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:tags,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tag = Tag::create(['name' => $request->input('name')]);

        Session::flash('flash_message', 'Tag added.');

        return redirect()->back();
    }
}
