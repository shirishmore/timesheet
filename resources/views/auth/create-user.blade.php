@extends('layouts.master')

@section('title')
Create new User -
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Add New User</h1>

    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif
  </div>
</div>

<div class="row">
  <div class="col-sm-4">
    <form action="{{ url('user/save') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <label>User Name</label>
        <input type="text" name="name"
        id="name" placeholder="Enter the user name"
        class="form-control" value="{{old('name')}}" />
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email"
        id="email" placeholder="Enter the user email"
        class="form-control" value="{{old('email')}}"/>
      </div>

      <div class="form-group">
        <label>Date of Birth</label>
        <input type="date" name="dob"
        id="dob" placeholder="Enter the Date of birth"
        class="form-control" value="{{old('dob')}}"/>
      </div>

      <div class="form-group">
        <label>Employee ID</label>
        <input type="text" name="employee_id"
        id="employee_id" placeholder="Enter the Employee ID"
        class="form-control" value="{{old('employee_id')}}"/>
      </div>

      <div class="form-group">
        <label>Joining Date</label>
        <input type="date" name="joining_date"
        id="joining_date" placeholder="Enter the Joining date"
        class="form-control" value="{{old('joining_date')}}" />
      </div>

      <div class="form-group">
        <label>User Role</label>
        <br>
        @foreach($roles as $role)
        <input type="checkbox" name="role[]" value="{{$role->id}}" /> {{$role->name}}
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password"
      id="password" placeholder="Enter the user password"
      class="form-control"/>
    </div>

    <button class="btn btn-primary">Add User</button>
  </form>
</div>

<div class="col-sm-8">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Emp ID</th>
        <th>Joining Date</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      @foreach($users as $user)
      <tr>
        <td class="col-sm-7">{{$user->name}}</td>
        <td class="col-sm-1">{{$user->employee_id}}</td>
        <td class="col-sm-2">{{$user->joining_date}}</td>
        <td align="right">
          <a href="{{ url('user/edit/' . $user->id) }}" class="btn"><i class="fa fa-pencil"></i></a>
          <a href="javascript:void();" class="btn user-delete" data-user-id="{{$user->id}}"><i class="fa fa-times"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {!! $users->render() !!}
</div>
</div>
@endsection
