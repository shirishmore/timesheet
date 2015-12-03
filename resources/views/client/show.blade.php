@extends('layouts.master')

@section('title', 'Clients -')

@section('content') 
<div class="container">
<div class="content"> 
	<h4>Showing {{ $client->name }}</h4>
    <div class="jumbotron ">        
        <p>
        	<strong>Client Name:</strong> {{ $client->name }}<br>       
            <strong>Status:</strong> {{ $client->status }}
        </p>
    </div>
</div>
</div>
@stop