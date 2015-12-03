@extends('layouts.master')

@section('title')
My Time entries -
@endsection

@section('content')
<div class="row">
  <div class="col-sm-12">
    <h1>My Time Entries</h1>
    <hr>
  </div>

  <div class="col-sm-12">
    @include('tracker.tracker-add-frm')
  </div>
</div>

<div class="row">
  <div class="col-sm-12">

    @foreach ($dataArr as $key => $data)
    <div class="time-entry clearfix">
      <h3>{{$key}} <span>{{$data['time']}}hrs</span></h3>
      @foreach ($data['entries'] as $tracker)
      <div class="col-sm-12 item" id="tracker-{{$tracker->id}}">
        <span class="desc">{{$tracker->desc}}</span>
        <span class="client">{{$tracker->client_name}}</span>
        <span class="project">{{$tracker->project_name}}</span>
        <span class="delete-tracker" data-tracker-id="{{$tracker->id}}"><i class="fa fa-times"></i></span>
        <span class="time">{{$tracker->time}}</span>
      </div>
      @endforeach
    </div>
    @endforeach

    {!! $trackers->render() !!}
  </div>
</div>
@endsection
