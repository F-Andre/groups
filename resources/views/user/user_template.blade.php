@extends('template')

@section('content')
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link" href="{{ route('user_page.index') }}">Mon compte<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="{{ route('user_page.show', ['id' => $user->id]) }}">Mes articles</a>
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
