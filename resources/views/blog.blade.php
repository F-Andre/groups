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
  <hr>
  <div class="accordion" id="accountAcc">
    <a id="headingGroup" class="btn btn-outline-success" href="#" role="button" data-toggle="collapse"
      data-target="#groupDropdown" aria-expanded="true" aria-controls="groupDropdown">
      {{ $groupName }}
      <i class="ml-2 fas fa-caret-down"></i>
    </a>
    <div id="groupDropdown" class="collapse mt-2" aria-labelledby="headingGroup" data-parent="#accountAcc">
      @if (in_array(auth()->user()->id, $groupAdmins))
      <a class="dropdown-item" href="{{ route('admin.index', $groupName) }}" role="button">Page admin</a>
      @endif
      <a class="dropdown-item" href="{{ route('group.index') }}">
        Mes groupes
      </a>
    </div>
  </div>
  <div class="mt-4">
    <a class="btn btn-outline-primary" href="{{ route('posts.create', $groupName) }}" role="button">Ecrire un
      article</a>
  </div>
</aside>
@endauth
<article class="mx-auto col-lg-6 py-4">
  @if (session()->has('ok'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (session()->has('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (count($posts) > 0 && $nbrPosts > 0)
  {{ $links }}
  @foreach ($posts as $post)
  @if ($post->group_id == $groupId)
  @include('templates/post_complete_template')
  @endif
  <br>
  @endforeach
  {{ $links }}
  @else
  <div class="mt-5 text-center">
    <p class="h2">Il n'y encore aucun article</p>
    <p class="h3">Lancez-vous!</p>
    <a class="btn btn-outline-success mt-5" href="{{ route('posts.create', $groupName) }}" role="button">Ecrire un
      article</a>
  </div>
  @endif
</article>
@endsection