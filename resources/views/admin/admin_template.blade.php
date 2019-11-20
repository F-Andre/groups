@extends('template')

@section('content')
@if (Auth::check() and in_array(auth()->user()->id, $groupAdmins))
<nav class="navbar navbar-expand-sm navbar-dark bg-secondary">
  <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId"
    aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavId">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.index', $groupName) }}" role="button">Page admin du groupe
          "{{ $groupName }}"
          <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    @isset($users)
    <form class="form-inline my-2 my-lg-0" method="POST" action={{ route('admin.searchResult', [$groupName, $user]) }}>
      @csrf
      <div class="form-group">
        <input class="form-control mr-sm-2" list="users" name="user" id="user-search-input" type="text"
          placeholder="Chercher un membre du groupe" autocomplete="off" />
        <datalist id="users">
          @foreach ($users as $user)
          @if (in_array($user->id, $groupUsers))
          <option value="{{ $user->name }}">
            @endif
            @endforeach
        </datalist>
      </div>
      <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Rechercher</button>
    </form>
    @endisset
  </div>
</nav>

<aside class="col-2 ml-5 py-4" id="admin-aside">
  @include('templates/nav_left_template')
</aside>
<section class="col-lg-8 offset-lg-3 pt-4">
  @yield('section')
</section>
@else
{{ view('welcome') }}
@endif

@endsection