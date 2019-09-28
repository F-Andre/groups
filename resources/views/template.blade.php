<!doctype html>
<html lang="fr">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://twemoji.maxcdn.com/2/twemoji.min.js?12.0.0"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
      integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  </head>

  <body>
    @php
      setlocale(LC_TIME, 'fr_FR');
    @endphp
    <div id="app">
      <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
        <div class="container col-11">
          <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul id="authBtn" class="navbar-nav ml-auto">
              @isset($groupName)
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('posts.create', $groupName) }}" role="button">Ecrire un article</a>
                </li>
              @endisset
              @if (Auth::user()->admin)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.index') }}" role="button">Page Admin</a>
              </li>
              @endif
              <li class="nav-item dropdown">
                <a id="authDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="authDropdown">
                  <a class="dropdown-item" href="{{ route('user_page.index') }}">
                    Mon compte
                  </a>
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Se déconnecter
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </div>
              </li>
            </ul>
            @endauth

            <!-- Right Side Of Navbar -->
            @guest
            <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Se connecter</a>
              </li>
              @if (Route::has('register'))
              <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Créer un compte</a>
              </li>
              @endif
            </ul>
            @endguest
          </div>
        </div>
      </nav>
      <main>
        @yield('content')
      </main>
    </div>


    <!-- Optional JavaScript -->
    <script>
      twemoji.parse(document.body);
    </script>
    <script src="/js/diffLoad.js"></script>
  </body>

</html>
