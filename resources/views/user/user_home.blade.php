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
      <p class="avatar" style={{"background-image:url(".Storage::url($user->avatar).")"}}></p>
      <p class="card-text">Compte créé le: {{ Date::parse($user->created_at)->format('d F Y') }}</p>
      <p class="card-text">Votre adresse e-mail: {{ $user->email }}</p>
      @if ($user->notifs == 'true')
        <p class="card-text">Vous recevez les notifications par mail</p>
      @else
        <p class="card-text">Vous ne recevez pas les notifications par mail</p>
      @endif
        <a role="button" class="btn btn-warning float-right"
          href="{{ route('user_page.edit', ['id' => $user->id]) }}">Editer mes informations</a>
    </div>
    @if ($user->admin == 1)
    <div class="card-footer text-danger">
      Vous êtes administrateur du site
    </div>
    @endif
  </div>
</article>
@endsection
