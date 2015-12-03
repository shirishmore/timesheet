@extends('layouts.master')

@section('title', 'Projects -')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>Showing {{ $project->name }}</h1>

    <div class="jumbotron">
      <p>
       <strong>Project Name:</strong> {{ $project->name }}<br>
       <strong>Client:</strong> {{ $project->client['name'] }}<br>
       <strong>Status:</strong> {{ $project->status }}
     </p>
   </div>
 </div>
</div>

@if (count($estimates) > 0)
<div class="row">
  <div class="col-sm-12">
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
</div>
@endif
@endsection
