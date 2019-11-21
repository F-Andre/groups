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
    <i class="fas fa-exclamation-triangle mr-2"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="card">
    <div class="card-header group-bg">
      <p class="h4">Les groupes</p>
    </div>
    <div class="card-body">
      <div>
        @isset($groups)
        <p class="h5">Mes groupes:</p>
        <div class="card-deck scrollable-div">
          @foreach ($groups as $group)

          @php
          $userArray = explode(",", $group->users_id);
          $onDemandArray = explode(",", $group->on_demand);
          $adminsArray = explode(",", $group->admins_id);
          $avatarUrl = Storage::url($group->avatar);
          $updated = Carbon\Carbon::parse($group->active_at)->locale('fr')->format('d M Y à
          H:i');
          @endphp

          @if (in_array(auth()->user()->id, $userArray))
          @include('templates/group_list_template')
          @endif
          @endforeach
        </div>
        <hr>
        <p class="h5">Rechercher un groupe:</p>
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
        <hr class="hr">
        <p class="h5">Tous les groupes:</p>
        <div class="card-deck mt-4 scrollable-div">
          @foreach ($groups as $group)

          @php
          $userArray = explode(",", $group->users_id);
          $avatarUrl = Storage::url($group->avatar);
          $updated = Carbon\Carbon::parse($group->active_at)->locale('fr')->format('d M Y à
          H:i');
          @endphp

          @if (!in_array(auth()->user()->id, $userArray) && $group->hidden == false)
          @include('templates/group_list_template')
          @endif
          @endforeach
        </div>
        @endisset
      </div>
      <hr class="hr">
      <div class="my-4">
        <a class="btn btn-primary" href={{ route('group.create') }} role="button">Créer un groupe</a>
      </div>
    </div>
  </div>
  @endsection