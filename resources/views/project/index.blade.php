@extends('layouts.master')

@section('title', 'Projects -')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h1>Projects</h1>
    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
  </div>
</div>

@if (Gate::allows('addProjects', new \App\Project))
<div class="row">
  <div class="col-sm-12">
    <a href="{{ URL::to('project/create') }}" class="btn btn-primary">Add Project</a>
    <br>
    <br>
  </div>
</div>
@endif

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <td>ID</td>
          <td>Name</td>
          <td>Client</td>
          <td>Status</td>
          @if (Gate::allows('addProjects', new \App\Project))
          <td>Actions</td>
          @endif
        </tr>
      </thead>
      <tbody>

        @foreach ($project as $value)

        <tr>
          <td>{!! $value['id'] !!}</td>
          @if (Gate::allows('addProjectEstimate', new \App\Estimate))
          <td><a href="{{ url('project/estimates/' . $value['id']) }}">{{ $value['name'] }}</a> <strong>({{$value->estimates->count()}})</strong></td>
          @else
          <td>{{ $value['name'] }}</td>
          @endif
          <td>{!! $value['client']['name'] !!}</td>
          <td>{!! $value['status'] !!}</td>
          @if (Gate::allows('addProjects', new \App\Project))
          <td>
            <a class="btn " href="{{ url('project/' . $value['id']) }}"><i class="fa fa-eye"></i> </a>
            <a class="btn " href="{{ URL::to('project/' . $value['id'] . '/edit') }}"><i class="fa fa-pencil"></i> </a>
            <a class="btn delete-project" data-id="{{ $value['id'] }}" href="javascript:void();"><i class="fa fa-times"></i></a>
            <a href="{{ url('project/estimates/' . $value['id']) }}" class="btn btn-info"><i class="fa fa-plus"></i> Add Estimate</a>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@stop
