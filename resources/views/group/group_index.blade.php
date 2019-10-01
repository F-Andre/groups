@extends('template')

@section('content')
@auth
<aside class="col-2 ml-5 py-4">
  <div class="accordion" id="authAcc">
    <a class="btn btn-secondary" id="headingAuth" href="#" role="button" data-toggle="collapse"
      data-target="#authDropdown" aria-expanded="true" aria-controls="authDropdown">
      <span class="avatar avatar-btn float-left"
        style="background-image: url({{ Storage::url(Auth::user()->avatar) }})"></span>
      {{ Auth::user()->name }}
      <i class="ml-2 fas fa-caret-down"></i>
    </a>
    <div id="authDropdown" class="collapse mt-2" aria-labelledby="headingAuth" data-parent="#authAcc">
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
  </div>
</aside>
@endauth
<div class="container-fluid col-lg-6 bx-auto mt-4">
  @if (session()->has('error'))
  <div class="col-lg-8 text-center mx-auto mt-4 alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="card">
    <div class="card-header">
      Choisir un groupe
    </div>
    <div class="card-body">
      <div>
        @isset($groups)
        <p>Mes groupes:</p>
        <div class="card-deck">
          @foreach ($groups as $group)

          @php
          $userArray = explode(",", $group->users_id);
          $avatarUrl = Storage::url($group->avatar);
          $updated = Carbon\Carbon::parse($group->active_at)->locale('fr')->timezone('Europe/Paris')->format('d M Y à
          H:i');
          @endphp

          @if (in_array(auth()->user()->id, $userArray))
          <div class="card flex-fill mb-2">
            <div class="card-body d-flex card-group">
              <a id={{ $group->name }} href={{ route('posts.index', $group->name) }}></a>
              <div class="mr-4">
                <img src={{ $avatarUrl }} class="avatar avatar-group" alt="{{ $group->name }}-image">
              </div>
              <div>
                <p class="card-title h5">{{ $group->name }}</p>
                <p class="card-text">{{ $group->description }}</p>
              </div>
            </div>
            <div class="card-footer">
              <p class="card-text"><small class="text-muted">Actif le : {{ $updated }}</small></p>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        <hr>
        <p>Rechercher un groupe:</p>
        <form class="form-inline my-2 my-lg-0" method="POST"
          action="{{ route('group.searchResult', ['groupSearch']) }}">
          @csrf
          <div class="form-group">
            <input class="form-control mr-sm-2" list="groups" name="groupSearch" id="groupSearch" type="text"
              placeholder="Entrez le nom d'un groupe" autocomplete="off" />
            <datalist id="groups">
              @foreach ($groups as $group)
              @php
              $userArraySeach = explode(",", $group->users_id);
              @endphp
              @if (!in_array(auth()->user()->id, $userArraySeach))
              <option value="{{ $group->name }}">
              @endif
              @endforeach
            </datalist>
          </div>
          <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Rechercher</button>
        </form>
        <div class="card-deck mt-4">
          @foreach ($groups as $group)

          @php
          $userArrayUnreg = explode(",", $group->users_id);
          $avatarUrlUnreg = Storage::url($group->avatar);
          $updatedUnreg = Carbon\Carbon::parse($group->active_at)->locale('fr')->timezone('Europe/Paris')->format('d M Y à
          H:i');
          @endphp

          @if (!in_array(auth()->user()->id, $userArrayUnreg))
          <div class="card flex-fill mb-2">
            <div class="card-body d-flex card-group">
              <a id={{ $group->name }} href={{ route('group.show', $group->name) }}></a>
              <div class="mr-4">
                <img src={{ $avatarUrlUnreg }} class="avatar avatar-group" alt="{{ $group->name }}-image">
              </div>
              <div>
                <p class="card-title h5">{{ $group->name }}</p>
                <p class="card-text">{{ $group->description }}</p>
              </div>
            </div>
            <div class="card-footer">
              <p class="card-text"><small class="text-muted">Actif le : {{ $updatedUnreg }}</small></p>
            </div>
          </div>
          @endif
          @endforeach
        </div>
        @endisset
      </div>
      <hr>
      <div class="my-4">
        <a class="btn btn-primary" href={{ route('group.create') }} role="button">Créer un groupe</a>
      </div>
    </div>
  </div>
  @endsection