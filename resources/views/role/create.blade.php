@extends('layouts.master')

@section('title', 'Create Role -')

@section('content') 

            <div class="container">
            <div class="content">  
            <h4>Create New Role</h4>
                @if ($errors->has())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>        
                            @endforeach
                        </div>
                @endif 
               <form method="POST" action="{!! url('role') !!}">                
                 <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">                         
                <div class="form-group">                   
                    <label>Role Name  :  </label>                   
                    <input type='text' class="form-control" name="name">                      
                </div>
                <div class="form-group">                    
                                                    
                </div>
                <div class="form-group">                  
                   <input type="submit" value="Create Role" class = "btn btn-primary ">
                </div>                
                </form>                 
            </div>
        </div>
@stop


