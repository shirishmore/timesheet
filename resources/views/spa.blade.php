@extends('layouts.master')

@section('title', 'Timesheet application')

@section('content')

<div ng-app="myApp">
  <div ng-controller="globalController" ng-view></div>
  <div class="snackbar-container" data-snackbar="true"></div>
</div>
@endsection
