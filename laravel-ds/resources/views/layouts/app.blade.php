<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $metaTitle ?? config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset(mix('js/app.js')) }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="/lk//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
</head>
<body>
  <div>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="background-image: url(http://pioneer-school.ru/css/images/header_bg.jpg);">


      <div class="container">
        <a class="navbar-brand" href="//pioneer-school.ru/">
          <img src="//pioneer-school.ru/css/images/pioner_logo.png" alt="" style="max-height:80px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
            @can('admin only')
            <li class="nav-item"><a class="nav-link text-light" href="/lk/user">Пользователи</a></li>
            <li class="nav-item dropdown">
              <a id="navbarDropdownQuiz" class="nav-link dropdown-toggle text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Опросы <span class="caret"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownQuiz">
                <a class="dropdown-item" href="/lk/quiz">Список опросов</a>
                <a class="dropdown-item" href="/lk/quiz-answer">Список ответов</a>
                <a class="dropdown-item" href="/lk/quiz/edit">Добавить опрос</a>
              </div>
            </li>
            @else
            <li class="nav-item"><a class="nav-link text-light" href="/lk/quiz">Список опросов</a></li>

            @endcan
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
            @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link text-light" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @endif

            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link text-light" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
                @can('admin only')
                <small>(Админ)</small>

                @endcan
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <main class="py-4">
      @yield('content')
    </main>
  </div>
</body>
</html>
