<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport'/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YouCompilations') }}</title>

    <link href="{{ asset('css/material-kit.min.css') }}" rel="stylesheet"/>

    <!-- Scripts -->
    <!--   Core JS Files   -->
    <script src="{{ asset('js/core/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/plugins/moment.min.js') }}"></script>
    <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
    <script src="{{ asset('js/plugins/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{ asset('js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
    <!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/material-kit.js') }}" type="text/javascript"></script>
    <!-- Fonts -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

    <style>
        p {
            font-size: 16px;
            margin: 0 0 10px;
            !important;
        }

        .header-filter::before {
            background: rgba(0,0,0,0.4);
            !important;
        }
        .dropdown-menu .dropdown-item:focus, .dropdown-menu .dropdown-item:hover, .dropdown-menu a:active, .dropdown-menu a:focus, .dropdown-menu a:hover {
            box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(156,39,176,.4);
            background-color: #b00f24;
            color: #FFF;
            !important;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-color-on-scroll fixed-top navbar-expand-lg navbar-transparent" color-on-scroll="100" id="sectionsNav">
    <div class="container">
        <div class="navbar-translate">
            <a class="navbar-brand" href="{{ route('index') }}">YouCompilations</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('compilations') . '/#scroll' }}">
                            My compilations
                            <div class="ripple-container"></div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') . '/#scroll' }}">
                            About
                            <div class="ripple-container"></div>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            {{--<a class="dropdown-item" href="{{ route('settings') . '/#scroll' }}">--}}
                                {{--Settings--}}
                            {{--</a>--}}
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                @endguest
            </ul>
        </div>
    </div>
</nav>

<div class="page-header header-filter" data-parallax="true"
     style="background-image: url({{ asset('img/2.jpg') }}); transform: translate3d(0px, 0px, 0px);">
    <div class="container">
        <div class="row">
            <div class="col-md-10 ml-auto mr-auto">
                <div class="brand">
                    <h1 class="title">YouCompilations</h1>
                    <h3>Be in trend. All the freshest and most popular especially for you.</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main main-raised">
    <div class="section">
        @yield('content')
    </div>
</div>

<footer class="footer" data-background-color="black">
    <div class="container">
        <nav class="float-left">
            <ul>
                <li>
                    <a href="https://roonyx.tech/">
                        Roonyx
                    </a>
                </li>
                <li>
                    <a href="https://roonyx.tech/#about">
                        About Us
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright float-right">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://roonyx.tech/" target="_blank">RoonyxTeam</a>.
        </div>
    </div>
</footer>

</body>
</html>
