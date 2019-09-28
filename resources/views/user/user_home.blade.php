@extends('user/user_template')

@section('article')
@if (session()->has('ok'))
<div class="col-lg-6 mx-auto mt-4 alert alert-success alert-dismissible fade show" role="alert">
  {{ session('ok') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
<article class="col-lg-8 mx-auto mt-4">
  <div class="card">
    <div class="card-header">
      <p class="h4 text-center">{{ $user->name }}</p>
    </div>
    <div class="card-body">
      <div class="my-4">
      <p class="h2">Mes groupes</p>
      @if (count($userGroups) > 0)
      <ul>
        @foreach ($userGroups as $group)
          <li><a href="{{ route('posts.index', $group->name) }}">{{ $group->name }}</a></li>
        @endforeach
      </ul>
      @else
        <p>Vous n'êtes inscrit dans aucun groupe.<br><br>Cliquez pour en rejoindre ou en créer un: </p>
        <a role="button" class="btn btn-primary"
        href="{{ route('group.index') }}">Groupes</a>
      @endif
      </div>
      <div>
        <p class="h2">Mes infos</p>
        <p class="avatar" style={{"background-image:url(".Storage::url($user->avatar).")"}}></p>
        <p class="card-text">Compte créé le: {{ Date::parse($user->created_at)->format('d F Y') }}</p>
        <p class="card-text">Votre adresse e-mail: {{ $user->email }}</p>
        @if ($user->notifs == 'true')
        <p class="card-text">Vous recevez les notifications par mail</p>
        @else
        <p class="card-text">Vous ne recevez pas les notifications par mail</p>
        @endif
        <a role="button" class="btn btn-warning float-right"
          href="{{ route('user_page.edit', $user->id) }}">Editer mes informations</a>
      </div>
    </div>
    @if ($user->admin == 1)
    <div class="card-footer text-danger">
      Vous êtes administrateur du site
    </div>
    @endif
  </div>
</article>
@endsection