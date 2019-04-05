@extends('template')

@section('content')
    @if (Auth::check() and Auth::user()->admin)
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.index') }}">Admin Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            @isset($users)
            <form class="form-inline my-2 my-lg-0" method="POST" action="{{ route('admin.searchResult', ['user']) }}">
                    @csrf
                    <div class="form-group">
                        <input class="form-control mr-sm-2" list="users" name="user" id="user" type="text" placeholder="Entrez un nom ou un e-mail" autocomplete="off"/>
                        <datalist id="users">
                            @foreach ($users as $user)
                                <option value="{{ $user->name }}">
                                <option value="{{ $user->email }}">
                            @endforeach
                        </datalist>
                    </div>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
                </form>
            @endisset
        </div>
    </nav>
    <section class="col-lg-10 mx-auto">
        @yield('section')
    </section>
    @else
    <div class="text-center mt-5">
        <p class="h4">Connectez-vous pour accéder à cette page.</p>
    </div>
    @endif

@endsection
