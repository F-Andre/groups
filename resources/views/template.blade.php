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
  @yield('script')

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>

<body>
  @php
  setlocale(LC_TIME, 'fr_FR');
  $cookiesAccept = Illuminate\Support\Facades\Cookie::get('cookiesAccept');
  if ($cookiesAccept == null )
  {
  $cookiesAccept = false;
  }
  @endphp
  @if (!$cookiesAccept)
  @include('templates/modal_cookies')
  @endif
  <div id="app">
    <nav class="navbar navbar-expand-xl navbar-light navbar-laravel">
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
          <ul id="authBtn" class="navbar-nav ml-auto mt-2">
            <li class="mt-2">
              <a class="btn btn-outline-secondary form-control mb-2" href="{{ route('group.index') }}">
                <i class="fas fa-home mr-2"></i>Accueil
              </a>
            </li>
            <li class="nav-item accordion" id="navAuthAcc">
              <a class="btn btn-secondary col-12 d-flex justify-content-center align-items-center flex-wrap mx-auto" id="navHeadingAuth" href="#" role="button"
                data-toggle="collapse" data-target="#navAuthDropdown" aria-expanded="true"
                aria-controls="navAuthDropdown">
                <span class="avatar avatar-btn mx-2"
                  style="background-image: url({{ Storage::url(Auth::user()->avatar) }})"></span>
                {{ Auth::user()->name }}
                <i class="ml-2 fas fa-caret-down"></i>
              </a>
              <div id="navAuthDropdown" class="collapse mt-2" aria-labelledby="navHeadingAuth"
                data-parent="#navAuthAcc">
                <a class="dropdown-item" href="{{ route('user_page.index') }}">
                  Mon compte
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  Se déconnecter
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
            @isset($groupName)
            @isset($groupAdmins)
            <li class="nav-item accordion mt-2" id="navAccountAcc">
              <a id="navHeadingGroup" class="btn btn-outline-groups col-12 d-flex justify-content-center align-items-center flex-wrap mx-auto" href="#" role="button"
                data-toggle="collapse" data-target="#navGroupDropdown" aria-expanded="true"
                aria-controls="navGroupDropdown">
                <span class="avatar avatar-btn mx-2"
                  style="background-image: url({{ Storage::url($group->avatar) }})"></span>
                {{ $groupName }}
                <i class="ml-2 fas fa-caret-down"></i>
              </a>
              <div id="navGroupDropdown" class="collapse mt-2" aria-labelledby="navHeadingGroup"
                data-parent="#navAccountAcc">
                <a class="dropdown-item" href="{{ route('posts.index', $groupName) }}">
                  Articles du groupe
                </a>
                <a class="dropdown-item" href="{{ route('group.show', $groupName) }}">
                  Infos du groupe
                </a>
                @if (in_array(auth()->user()->id, $groupAdmins))
                <a class="dropdown-item" href="{{ route('admin.index', $groupName) }}" role="button">Page admin</a>
                @endif
              </div>
            </li>
            <li class="mt-2">
              <a class="btn btn-outline-primary form-control" href="{{ route('posts.create', $groupName) }}"
                role="button">Ecrire un
                article</a>
            </li>
            @endisset
            @endisset
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
    <main class="pb-5">
      @yield('content')
    </main>
    @include('templates/help_modal')
  </div>
  <footer>
    <div class="d-flex justify-content-between align-items-center">
      <p><small>Tous droits réservés - 2019</small></p>
      <button type="button" class="btn mx-xl-auto" data-toggle="modal" data-target="#helpModal">
        <i id="help" class="far fa-question-circle fa-2x"></i>
      </button>
    </div>
  </footer>
  <!-- Optional JavaScript -->
  <script>
    twemoji.parse(document.body);
  </script>
</body>

</html>