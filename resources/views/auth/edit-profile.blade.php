@extends('layouts.master')

@section('title', 'Edit User -')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Edit your profile </h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    @if (Gate::allows('addProjectEstimate', new \App\Estimate))
    <form action="{{ url('user/save-profile-update') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <input type="text" name="name"
        id="name" placeholder="Enter your display Name"
        class="form-control" value="{{$user->name}}" />
      </div>

      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="dob"
        id="dob" placeholder="Enter the Date of birth"
        class="form-control" value="{{$user->dob}}"/>
      </div>

      <div class="form-group">
        <label>Employee ID</label>
        <input type="text" name="employee_id"
        id="employee_id" placeholder="Enter the Employee ID"
        class="form-control" value="{{$user->employee_id}}"/>
      </div>

      <div class="form-group">
        <label>Joining Date</label>
        <input type="date" name="joining_date"
        id="joining_date" placeholder="Enter the Joining date"
        class="form-control" value="{{$user->joining_date}}" />
      </div>

      <button class="btn btn-primary">Save</button>
    </form>
    @endif
  </div>
</div>
@endsection
