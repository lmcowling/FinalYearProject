<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy Football Predictions League</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" >
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Scripts -->
<script>
    window.Laravel = echo json_encode(['csrfToken' => csrf_token(),]);
</script>
</head>
<body>
  <nav class="navbar-default navbar-inverse">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="{{ url('home') }}">Fantasy Football Predictions League</a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="{{ (Request::is('predictions') ? 'active' : '') }}">
            <a href="{{ url('predictions') }}">Predictions</a>
          </li>
          <li class="{{ (Request::is('leagues') ? 'active' : '') }}">
            <a href="{{ url('leagues') }}">Leagues</a>
          </li>
          <li class="{{ (Request::is('tables') ? 'active' : '') }}">
            <a href="{{ url('tables') }}">Tables</a>
          </li>
          <li class="{{ (Request::is('about') ? 'active' : '') }}">
            <a href="{{ url('about') }}">About</a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          @if (Auth::guest())
            <li class="{{ (Request::is('auth/login') ? 'active' : '') }}">
                <a href="{{ url('auth/login') }}">
                    <i class="fa fa-sign-in"></i> Login</a>
            </li>
            <li class="{{ (Request::is('auth/register') ? 'active' : '') }}"><a
                href="{{ url('auth/register') }}">Register</a></li>
              @else
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                  aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                  class="fa fa-caret-down"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    @if(Auth::check())
                      @if(Auth::user()->admin==1)
                        <li>
                          <a href="{{ url('admin/dashboard') }}"><i class="fa fa-tachometer"></i> Admin Dashboard</a>
                        </li>
                      @endif
                      <li role="presentation" class="divider"></li>
                    @endif
                    <li>
                      <a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                  </ul>
                </li>
              @endif
            </ul>
          </div>
        </div>
      </nav>
      <div class="container">
          @yield('content')
      </div>
    </body>
    </html>
