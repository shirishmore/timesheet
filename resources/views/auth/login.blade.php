@extends('layouts.master')

@section('content')
<div class="row" style="margin-top: 130px;">
  <div class="col-md-4 col-md-push-4 well">
    <h1>Login</h1>
    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    @if ($errors->has())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach
    </div>
    @endif

    <form method="POST" action="login">
      {!! csrf_field() !!}

      <div class="form-group">
        <input type="email" name="email"
        class="form-control" value="{{ old('email') }}"
        placeholder="Enter your email address">
      </div>

      <div class="form-group">
        <input type="password" name="password"
        id="password" placeholder="Enter your password"
        class="form-control"/>
      </div>

      <div>
        <button class="btn btn-primary">Login</button>
      </div>
    </form>
  </div>
</div>
@endsection
