@extends('layouts.master')

@section('title', 'Edit Project')

@section('content')
<div class="container">
  <h1>Edit {{ $project->name }}</h1>

  <!-- if there are creation errors, they will show here -->
  @if ($errors->has())
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
    {{ $error }}<br>
    @endforeach
  </div>
  @endif

  <form action="{!! url('project') !!}">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
      <label>Name  :  </label>
      <input type='text' class="form-control" name="name" value="{{ $project->name }}">
    </div>

    <div class="form-group">
      <label>Client</label>
      <select name="project" class="form-control">
        @foreach($project as $k => $v)
        <option value="{{$k}}">{{$v}}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <input type="submit" value="Edit Project" class = "btn btn-primary">
    </div>
  </form>

</div>
@stop
