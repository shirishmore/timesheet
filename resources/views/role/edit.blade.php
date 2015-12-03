@extends('layouts.master')

@section('title', 'Edit Role')

@section('content')
<div class="container">
  <h1>Edit {{ $role->name }}</h1>

  <!-- if there are creation errors, they will show here -->
  @if ($errors->has())
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
    {{ $error }}<br>
    @endforeach
  </div>
  @endif

  <form action="{!! url('role') !!}">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
      <label>Name  :  </label>
      <input type='text' class="form-control" name="name" value="{{ $role->name }}">
    </div>

    <div class="form-group">
      <input type="submit" value="Edit Role" class = "btn btn-primary">
    </div>
  </form>

</div>
@stop