 @extends('layouts.master')

@section('title', 'Edit Client')

@section('content') 
<div class="container">
<h1>Edit {{ $client->name }}</h1>

<!-- if there are creation errors, they will show here -->
    @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
    @endif

  <form action="{!! url('clients') !!}">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">   
    <div class="form-group">  
        <label>Name  :  </label>                   
        <input type='text' class="form-control" name="name" value="{{ $client->name }}">
    </div>

    <div class="form-group">
      <label>Country  :  </label>  
      <select name="country">
      <option value="IN">IN</option>
      <option value="US">US</option>
      <option value="UK">UK</option>                     
      </select>
    </div>

    <div class="form-group">
         <input type="submit" value="Edit Client" class = "btn btn-primary">
    </div>
    </form>

</div>
@stop