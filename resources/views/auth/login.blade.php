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
<div class="login-container">
    <div>
            
                <div class="logo"><img src="{{ asset('/img/logo.png') }}" alt=""></div>

                <div class="login-form-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="login-form-group">
                            <input id="email" type="email" class="login-email-input form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="login-form-group">
                            <div class="col-md-6">
                                <input id="password" type="password" class="login-password-input form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="パスワード">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="login-form-submit">
                                <div class="login-form-remember">
                                    <div class="remember-input-wrap">
                                        <input class="remember-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="remember-check-part"></span>
                                    </div>
                                    <label class="login-form-check-label" for="remember">
                                        <span>ログイン状態を保持</span>
                                    </label>
                                </div>
                                <button type="submit" class="crud-btn login-form">
                                    ログイン
                                </button>
                        </div>

                        <div class="login-form-request">
                            @if (Route::has('password.request'))
                                <a class="password-forget" href="{{ route('password.request') }}">
                                    パスワードを忘れてしまった場合
                                </a>
                            @endif
                        </div>
                            
                    </form>
                </div>
            
        
    </div>
</div>
</body>
</html>
