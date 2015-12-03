@extends('layouts.master')

@section('title', 'Roles -')

@section('content') 

            <div class="container">
            <div class="content">  
             Roles Listings 
<!-- will be used to show any messages -->
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

<input type="hidden" name="_token" value="{{ csrf_token() }}">
<a href="{{ URL::to('role/create') }}">Create New Role</a>   
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Status</td>          
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($role as $value) 
        <tr>
            <td>{!! $value['id'] !!}</td>
            <td>{!! $value['name'] !!}</td>
            <td>{!! $value['status'] !!}</td>           
            <td>

              <a class="btn " href="{!! URL::to('role/' . $value['id']) !!}"><i class="fa fa-eye"></i> </a>
              <a class="btn " href="{!! URL::to('role/' . $value['id'] . '/edit') !!}"><i class="fa fa-pencil"></i> </a>
              <a class="btn delete-role" data-id="{!! $value['id'] !!}" href="javascript:void();"><i class="fa fa-times"></i></a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
 
            </div>
        </div>
@stop



