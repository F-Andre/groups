@extends('template')

@section('content')
@auth
<aside class="col-2 ml-5 py-4">
  <div class="dropdown">
    <a id="authDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown"
      aria-haspopup="true" aria-expanded="false" v-pre>
      <span class="avatar avatar-btn float-left"
        style="background-image: url({{ Storage::url(Auth::user()->avatar) }})"></span>
      {{ Auth::user()->name }}
      <span class="caret"></span>
    </a>
    <div class="dropdown-menu" aria-labelledby="authDropdown">
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
  @if (Auth::user()->admin)
  <div class="mt-4">
    <a class="btn btn-outline-success" href="{{ route('admin.index') }}" role="button">page admin</a>
  </div>
  @endif
</aside>
@endauth
<div class="container-fluid col-lg-6 bx-auto mt-4">
  @if (session()->has('ok'))
  <div class="col-lg-10 mx-auto mt-4 alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (session()->has('error'))
  <div class="col-lg-10 mx-auto mt-4 alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <div>
        <p class="h3">{{ $group->name }}</p>
        <p>{{ $group->description }}</p>
        <p class="card-title">Créé le: {{ $dateCreation }}</p>
      </div>
      <p class="avatar" style={{"background-image:url(".Storage::url($group->avatar).")"}}></p>
    </div>
    <div class="card-body">
      @if (in_array(auth()->user()->id, $usersId))
      <p>Vous êtes membre de ce groupe</p>
      @else

      <form method="POST"
        action="{{ route('group.joinDemand', ['groupName' => $group->name, 'userId' => auth()->user()->id]) }}"
        enctype="multipart/form-data">
        @csrf
        <input name="joinGroup" id="joinGroup" class="btn btn-success" type="submit" value="Rejoindre">
      </form>
      @endif
    </div>
  </div>
  @endsection