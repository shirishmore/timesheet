@extends('layouts.master')

@section('title', 'Clients -')

@section('content')

<div class="row">
  <div class="col-sm-12">
    <h1>Clients</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif
  </div>
</div>

@if (Gate::allows('addClient', new \App\Client))
<div class="row">
  <div class="col-sm-12">
    <a href="{{ url('clients/create') }}" class="btn btn-primary">Create New Client</a>
    <br>
    <br>
  </div>
</div>
@endif

<div class="row" >
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <td>ID</td>
          <td>Name</td>
          <td>Country</td>
          <td>Status</td>
          <td>Actions</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($clients as $value)
        <tr>
          <td>{{ $value->id }}</td>
          <td>{{ $value->name }}</td>
          <td>{{ $value->country }}</td>
          <td>{{ $value->status }}</td>
          <td >
            <a class="btn" href="{!! URL::to('clients/' . $value['id']) !!}"><i class="fa fa-eye"></i> </a>
            <a class="btn" href="{!! URL::to('clients/' . $value['id'] . '/edit') !!}"><i class="fa fa-pencil"></i></a>
            <a class="btn delete-client" data-id="{!! $value['id'] !!}" href="javascript:void();"><i class="fa fa-times"></i></a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>

@endsection
