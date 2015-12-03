@extends('layouts.master')

@section('title', 'Create Project')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h1>Create Project</h1>
    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif
  </div>

  <div class="row">
    <div class="col-sm-12">
      <form method="POST" action="{!! url('project') !!}">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Project Name</label>
          <input type='text' class="form-control" name="name" placeholder="Enter Project name" />
        </div>

        <div class="form-group">
          <label>Client</label>
          <select name="client" class="form-control">
            @foreach($client as $k => $v)
            <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>

        <button class="btn btn-primary">Create Project</button>

      </form>
    </div>
  </div>
  @endsection
