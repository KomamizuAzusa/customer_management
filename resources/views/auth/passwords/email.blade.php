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
                    <p class="forget-password-title">パスワード再設定</p>
                    <p class="forget-password-text">
                    「パスワード再設定ページのURL」を、登録メールアドレスに送信します。<br>
                    登録メールアドレスを入力し【送信する】を押してください。
                    </p>
                    
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="email-form">
                                <input id="email" type="email" class="login-email-input form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="メールアドレス">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror  
                        </div>
                        <div class="forget-password-submit">
                            <button type="submit" class="crud-btn email">
                                送信する
                            </button>
                        </div>
                    </form>
                </div>
    </div>
</div>

</body>
</html>
