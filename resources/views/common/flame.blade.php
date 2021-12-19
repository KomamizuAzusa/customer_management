<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1280" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>顧客管理システム</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
    <!-- topBar -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('cssStyle')

    <!-- CDN -->
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.4/sweetalert2.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.15.4/sweetalert2.js"></script>
    <script src="{{ asset('/js/sweetAlert2.js') }}"></script>

    <!-- topBar -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body>
    <header class="header">
        <div class="header-inner inner">

            <h1 class="header-title">
                <a href="{{ route('customers') }}">
                    顧客管理システム
                </a>
            </h1>
            <div class="header-main">
                <nav class="header-nav">
                    <ul class="nav-list">
                        <li class="nav-item customer active">
                            <a href="{{ route('customers') }}">顧客情報</a>
                        </li>
                    </ul>
                </nav>
                <ul class="user-items">
                        @guest
                        <li class="user-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="user-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="user-item">
                            {{ Auth::user()->name }}
                        </li>
                        <li class="user-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endguest
                </ul>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>

    <script src="{{ asset('/js/common.js') }}"></script>
    @yield('js')
</body>

</html>