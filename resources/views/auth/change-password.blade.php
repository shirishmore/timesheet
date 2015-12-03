@extends('layouts.master')

@section('title')
Change password -
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Change your password</h1>

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
  <div class="col-sm-12">
    <form action="{{ url('user/save-new-password') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <label>Current password</label>
        <input type="password" name="current"
        class="form-control" placeholder="Enter your current password"
        tabindex="1" />
      </div>

      <div class="form-group">
        <label>New password</label>
        <input type="password" name="new"
        class="form-control" placeholder="Enter your new password"
        tabindex="2" />
      </div>

      <div class="form-group">
        <label>Confirm password</label>
        <input type="password" name="confirm"
        class="form-control" placeholder="Confirm your new password"
        tabindex="3" />
      </div>

      <button class="btn btn-primary">Change</button>

    </form>
  </div>
</div>

@endsection
