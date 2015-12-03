@extends('layouts.master')

@section('title', 'Edit Estimate')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h1>Edit estimate</h1>
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
    <form action="{{ url('project/estimates/update') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <input type="hidden" name="project_id" value="{{ $estimate->id }}">

      <div class="form-group">
        <input type="text" name="project_name"
        id="project_name" placeholder="Project name"
        class="form-control" value="{{$estimate->project->name}}" disabled />
      </div>

      <div class="form-group">
        <input type="text" name="desc"
        id="desc" placeholder="Enter the description of the estimate"
        class="form-control" value="{{ $estimate->desc }}" />
      </div>

      <div class="form-group">
        <input type="number" name="hours_allocated"
        id="hours_allocated" placeholder="Enter allocated hours"
        class="form-control" tabindex="2" step="any" value="{{ $estimate->hours_allocated }}"/>
      </div>

      <div class="form-group">
        <select class="form-control" name="status">
          <option value="{{ $estimate->status }}">{{ $estimate->status }}</option>
          @if ($estimate->status == 'In progress')
          <option value="Completed">Completed</option>
          @else
          <option value="In progress">In progress</option>
          @endif
        </select>
      </div>

      <a href="{{ url('project/estimates/' . $estimate->project->id) }}" class="btn btn-warning">Back</a>
      <button class="btn btn-success">Save</button>

    </form>
  </div>
  @endsection
