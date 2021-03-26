<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body class="welcome">
  @php
  $cookiesAccept = Illuminate\Support\Facades\Cookie::get('cookiesAccept');
  if ($cookiesAccept == null )
  {
  $cookiesAccept = false;
  }
  @endphp
  <div class="flex-center position-ref full-height">
    @if (!$cookiesAccept)
    @include('templates/modal_cookies')
    @endif
    <div class="content">
      <div class="title m-b-md">
        {{ config('app.name')}}
      </div>
      {{--<h1 class="mb-4">Plateforme de discussion qui respecte la vie privée</h1>--}}
      <div class="links d-flex justify-content-center flex-wrap">
        <div class="flex-fill mb-3">
          <a href="{{ route('login') }}">S'identifier</a>
        </div>
        <div class="flex-fill">
          <a href="{{ route('register') }}">Créer un compte</a>
        </div>
      </div>
      {{--<div class="d-flex justify-content-center mt-5">
        <button type="button" class="btn" data-toggle="modal" data-target="#helpModal">
          <i id="help" class="far fa-question-circle fa-3x"></i>
        </button>
      </div>--}}
    </div>
  </div>
  {{--@include('templates/help_modal')--}}
</body>

</html>