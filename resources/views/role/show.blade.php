@extends('layouts.master')

@section('title', 'Roles -')

@section('content') 
<div class="container">
<div class="content"> 
	<h4>Showing {{ $role->name }}</h4>
    <div class="jumbotron ">        
        <p>
        	<strong>Role :</strong> {{ $role->name }}<br>       
            <strong>Status:</strong> {{ $role->status }}
        </p>
    </div>
</div>
</div>
@stop