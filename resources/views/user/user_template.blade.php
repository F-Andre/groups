@extends('template')

@section('content')
<nav class="navbar navbar-expand navbar-dark bg-secondary">
  <div class="nav navbar-nav">
    <div class="dropdown">
      <a id="authDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false" v-pre>
        {{ Auth::user()->name }} <span class="caret"></span>
      </a>
      <div class="dropdown-menu" aria-labelledby="authDropdown">
        <a class="dropdown-item" href="{{ route('user_page.index') }}">Mon compte</a>
        <a class="dropdown-item" href="{{ route('logout') }}"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se déconnecter</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('user_page.show', ['id' => $user->id]) }}" role="button">Mes articles</a>
    </li>
    @if ($user->admin)
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('admin.index') }}" role="button">Page Admin</a>
    </li>
    @endif
  </div>
</nav>
@if (session()->has('ok'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  {{ session('ok') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@yield('article')
@endsection
