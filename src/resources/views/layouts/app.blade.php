<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ '仮想本棚 〜VirtualBookshelf〜' }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/boot.css') }}" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <!-- Font Awesome -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            @guest
                <a class="navbar-brand text-dark mr-3 custom-form" href="{{ url('/') }}">
                    {{ ' 仮想本棚 ' }}
                </a>
                <a class="navbar-brand text-dark mr-0 custom-form" href="{{ url('/users/all') }}">
                    <i class="fa fa-user-friends"></i>
                </a>
                <a class="navbar-brand text-dark mr-0 custom-form ml-2" href="{{ url('/products/search') }}">
                    <i class="fa fa-search"></i>
                </a>
            @else
                <a class="navbar-brand text-dark mr-3 custom-form" href="{{ url('/') }}">
                    {{ ' 仮想本棚 ' }}
                </a>
                <a class="navbar-brand text-dark mr-2 custom-form" href="{{ url('/users') }}">
                    <i class="fa fa-user-friends"></i>
                </a>
                <a class="navbar-brand text-dark mr-0 custom-form mx-2" href="{{ url('/products/search') }}">
                    <i class="fa fa-search"></i>
                </a>
                <a href="{{ url('users/' .auth()->user()->id) }}">
                    <img src="{{ asset('https://s3-ap-northeast-1.amazonaws.com/virtualbookshelf/' .auth()->user()->profile_image) }}" class="rounded-circle ml-2" width="40" height="40">
                </a>
            @endguest

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('register'))
                        <a class="btn btn-dark" href="{{ url('login') }}">ログイン / 新規登録</a>
                    @endif
                @else
                    <div class="d-flex flex-row">
                        <a href="{{ url('ProductForm') }}" class="btn btn-dark mr-3"><i class="far fa-edit mr-1"></i>投稿</a>
                    </div>
                @endguest
            </ul>
        </div>
    </nav>

    <main class="py-1 my-4">
        @yield('content')
    </main>
</body>
</html>
