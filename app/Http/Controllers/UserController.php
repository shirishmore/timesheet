<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Input;
use View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'doLogin']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }

        $userdata = ['email' => $request->input('email'), 'password' => $request->input('password')];

        if (Auth::attempt($userdata)) {
            $allowed = ['Admin', 'Project Manager'];

            $userRoles = User::roles();

            foreach ($userRoles as $role) {
                if (in_array($role->role, $allowed)) {
                    return redirect()->intended('spa/time-tracker-report');
                }
            }

            return redirect()->intended('home');
        }

        Session::flash('message', 'There was a problem logging you in. Please check your credentials and try again');
        return redirect('/');
    }

    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return response('user logged out', 200);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        $users = User::orderBy('name')->paginate(20);

        return view('auth.create-user', compact('users', 'roles'));
    }

    public function saveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|unique:users,email',
            'dob' => 'required',
            'employee_id' => 'required',
            'joining_date' => 'required',
            'role' => 'required',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'dob' => $request->input('dob'),
                'employee_id' => $request->input('employee_id'),
                'joining_date' => $request->input('joining_date'),
                'password' => Hash::make($request->input('password')),
            ]);

            foreach ($request->input('role') as $roleId) {
                DB::table('roles_users')->insert([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            Session::flash('flash_message', 'User was created');

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            Session::flash('flash_error', 'User was not created');
            \Log::info(print_r($e->getMessage(), 1));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return redirect()->back();
    }

    /**
     * Return the view for the change password form.
     *
     * @return View
     */
    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function saveNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current' => 'required',
            'new' => 'required|min:5',
            'confirm' => 'required|min:5|same:new',
        ], ['same' => 'New and Confirm password should match.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail(Auth::user()->id);
        $user->password = Hash::make($request->input('confirm'));
        $user->save();

        Session::flash('flash_message', 'Password changed successfully');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        Session::flash('flash_message', 'User ' . $user->name . ' deleted.');
        return redirect('user/add');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('auth.edit-user', compact('user'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|min:3',
            'dob' => 'required',
            'employee_id' => 'required',
            'joining_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($request->input('user_id'));

        $user->name = $request->input('name');
        $user->dob = $request->input('dob');
        $user->employee_id = $request->input('employee_id');
        $user->joining_date = $request->input('joining_date');

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        Session::flash('flash_message', 'User profile changed');
        return redirect()->back();
    }

    public function editProfile()
    {
        $user = User::find(Auth::user()->id);
        return view('auth.edit-profile', compact('user'));
    }

    public function saveProfileUpdate(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->input('name');
        $user->dob = $request->input('dob');
        $user->employee_id = $request->input('employee_id');
        $user->joining_date = $request->input('joining_date');
        $user->save();

        Session::flash('flash_message', 'Profile updated');
        return redirect()->back();
    }
}
