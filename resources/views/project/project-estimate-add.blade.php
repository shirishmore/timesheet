@extends('layouts.master')

@section('title', 'Add Estimate -')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Add Project Estimate for "{{$project->name}}"</h1>
    <hr>

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
  {{-- Column for Form --}}
  <div class="col-md-4">
    <form action="{{ url('project/estimates/save') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="project_id" value="{{ $id }}">

      <div class="form-group">
        <input type="text" name="desc"
        id="desc" placeholder="Enter the description"
        class="form-control" tabindex="1" />
      </div>

      <div class="form-group">
        <input type="number" name="hours_allocated"
        id="hours_allocated" placeholder="Enter allocated hours"
        class="form-control" tabindex="2" step="any"/>
      </div>

      <button class="btn btn-primary">Add</button>
    </form>
  </div>

  @if (count($estimates) > 0)
  <div class="col-md-8">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Desc</th>
          <th>Allocated</th>
          <th>Used</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($estimates as $estimate)
        <tr>
          <td><a href="{{ url('project/estimates/edit/' . $estimate->id) }}">{{$estimate->desc}}</a></td>
          <td>{{$estimate->hours_allocated}}</td>
          <td>{{$estimate->hours_consumed}}</td>
          <td>{{$estimate->status}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {!! $estimates->render() !!}
  </div>
  @endif
</div>
@endsection
