@extends('layouts.master')

@section('title', 'Create Client')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h1>Create New Client</h1>
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
    <form method="POST" action="{!! url('clients') !!}">
      {{ csrf_field() }}
      <div class="form-group">
        <label>Name</label>
        <input type='text' class="form-control" name="name">
      </div>

      <div class="form-group">
        <label>Country  :  </label>
        <select name="country" class="form-control">
          <option value="IN">IN</option>
          <option value="US">US</option>
          <option value="UK">UK</option>
        </select>
      </div>

      <div class="form-group">
        <input type="submit" value="Create Client" class = "btn btn-primary">
      </div>
    </form>
  </div>
</div>
@endsection
