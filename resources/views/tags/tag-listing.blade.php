@extends('layouts.master')

@section('title')
Tags -
@endsection

@section('content')
@if (Gate::allows('add', new \App\Tag))
<div class="row">
  <div class="col-sm-12">
    <h3>Add Tag</h3>
    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif
    <form action="{{ url('tags/save') }}" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <input type="text" name="name"
        id="name" placeholder="Enter tag name"
        class="form-control" value="{{ old('name') }}" />
      </div>

      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</div>
@endif

<div class="row">
  <div class="col-sm-12">
    <h3>All Tags</h3>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Name</th>
          @if (Gate::allows('delete', new \App\Tag))
          <th>Delete</th>
          @endif
        </tr>
      </thead>

      <tbody>
        @foreach ($tags as $tag)
        <tr>
          <td>{{$tag->name}}</td>
          @if (Gate::allows('delete', new \App\Tag))
          <td align="right">Delete</td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
