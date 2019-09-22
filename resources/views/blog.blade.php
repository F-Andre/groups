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
  <hr>
  <div class="dropdown">
    <a id="groupDropdown" class="btn btn-outline-success dropdown-toggle" href="#" role="button" data-toggle="dropdown"
      aria-haspopup="true" aria-expanded="false" v-pre>
      {{ $groupName }}
      <span class="caret"></span>
    </a>
    <div class="dropdown-menu" aria-labelledby="groupDropdown">
      <a class="dropdown-item" href="{{ route('group.index') }}" >
        Mes groupes
      </a>
    </div>
  </div>
  <div class="mt-4">
    <a class="btn btn-outline-primary" href="{{ route('blog.create', $groupName) }}" role="button">Ecrire un article</a>
  </div>
  @if (Auth::user()->admin)
  <div class="mt-4">
    <a class="btn btn-outline-success" href="{{ route('admin.index') }}" role="button">page admin</a>
  </div>
  @endif
</aside>
@endauth
<article class="mx-auto col-lg-6 py-4">
  @if (session()->has('ok'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('ok') }}
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
    <a class="btn btn-outline-success mt-5" href="{{ route('blog.create', $groupName) }}" role="button">Ecrire un
      article</a>
  </div>
  @endif
</article>
@endsection