<!DOCTYPE html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') Tranvas</title>
  <link rel="stylesheet" href="{{ url('vendor/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ url('vendor/paper.min.css') }}" />
  <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="{{ url('vendor/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/animate.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/select.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/angular-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/angular-chart.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/angular.snackbar.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/loading-bar.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendor/css/textAngular.css') }}">
  <link rel="stylesheet" href="{{ elixir('css/app.css') }}" />

  <style type="text/css">
    *, p, body, h1, h2, h3, h4, h5, h6 {
      font-family: 'Source Sans Pro', sans-serif;
    }
  </style>
  <script> var baseUrl = "{{url('/')}}/";</script>

  @yield('meta_tags')
</head>
<body>
  <div class="container">

    @if (Auth::user())
    @include('layouts.nav')
    @endif

    @if(Session::has('flash_message'))
    <div class="alert alert-success">{{Session::get('flash_message')}}</div>
    @endif

    @if(Session::has('flash_error'))
    <div class="alert alert-danger">{{Session::get('flash_error')}}</div>
    @endif

    @yield('content')
  </div>

  <script type="text/javascript" src="{{ url('vendor/jquery-1.11.3.min.js')  }}"></script>
  <script type="text/javascript" src="{{ url('vendor/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/script.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular-cookies.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular-route.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/select.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/select-tpls.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/Chart.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular-chart.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/angular.snackbar.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/loading-bar.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/textAngular-rangy.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/textAngular-sanitize.min.js') }}"></script>
  <script type="text/javascript" src="{{ url('vendor/textAngular.min.js') }}"></script>

  <script type="text/javascript" src="{{ elixir('js/app.js') }}"></script>
  @yield('scripts')
</body>
</html>
