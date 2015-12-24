<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{url('/home')}}">FW Labs</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        {{-- <li><a href="{{ url('project') }}"><i class="fa fa-product-hunt"></i> Projects</a></li> --}}
        @if (Gate::allows('viewClients', new \App\Client))
        <li><a href="{{ url('clients') }}"><i class="fa fa-user"></i> Clients</a></li>
        @endif
        <li><a href="{{ url('tags') }}"><i class="fa fa-tags"></i> Tags</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-clock-o"></i> Time Tracker <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('time-tracker') }}">My Entries</a></li>
            <li><a href="{{ url('time-tracker-add') }}">Add Entry</a></li>
            <li><a href="{{ url('spa/time-tracker-report#/user/request-backdate-entry') }}"><i class="fa fa-backward"></i> Request backdate entry</a></li>
            @if (Gate::allows('viewTrackerReport', new \App\TimeEntry))
            <li role="separator" class="divider"></li>
            <li><a href="{{ url('spa/time-tracker-report#/manage/back-date-entry') }}"><i class="fa fa-backward"></i> Backdate Entry</a></li>
            <li><a href="{{ url('spa/time-tracker-report#/projects') }}"><i class="fa fa-briefcase"></i> Projects</a></li>
            <li><a href="{{ url('spa/time-tracker-report') }}"><i class="fa fa-table"></i> Reports</a></li>
            <li><a href="{{ url('spa/time-tracker-download') }}"><i class="fa fa-file-excel-o"></i> Download Report</a></li>
            @endif
          </ul>
        </li>

        @if (Gate::allows('manageUsers', Auth::user()))
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-users"></i> User <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('role') }}">Roles</a></li>
            <li><a href="{{ url('user/add') }}">List Users</a></li>
          </ul>
        </li>
        @endif
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{url('user/edit-profile')}}"><i class="fa fa-user"></i> Edit profile</a></li>
            <li><a href="{{url('user/change-password')}}"><i class="fa fa-key"></i> Change password</a></li>
            <li role="separator" class="divider"></li>
            {{-- <li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i> Logout</a></li> --}}
            <li><a href="{{url('spa/time-tracker-report#/logout')}}"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
