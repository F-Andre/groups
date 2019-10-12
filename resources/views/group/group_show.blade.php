@extends('template')

@section('content')
@auth
<aside class="col-2 ml-5 py-4">
  <div class="dropdown">
    <a id="authDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
      aria-expanded="false" v-pre>
      <span class="avatar avatar-btn float-left" style="background-image: url({{ Storage::url(Auth::user()->avatar) }})"></span>
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
    <div class="card-header d-flex flex-wrap-reverse justify-content-between align-items-center">
      <div>
        <p class="h3">{{ $group->name }}</p>
        <p>{{ $group->description }}</p>
        <p class="card-title">Créé le: {{ $dateCreation }}</p>
      </div>
      <p class="avatar" style={{"background-image:url(".Storage::url($group->avatar).")"}}></p>
    </div>
    <div class="card-body">
      @if (in_array(auth()->user()->id, $usersId))
      <div class="d-flex justify-content-between">
        <p class="align-self-center m-0">Vous êtes membre de ce groupe</p>
        <div>
          <a class="btn btn-outline-success" href={{ route('posts.index', ['groupName' => $group->name]) }} role="button">
            Fil du groupe
          </a>
        </div>
      </div>
      <hr class="hr">
      <div class="mb-5">
        <p>Les autres membres:</p>
        <div class="card-deck">
          @foreach ($users as $user)
          @if (auth()->user()->id !== $user->id && in_array($user->id, $usersId))
          <div class="card">
            <div class="card-body m-0">
              <span class="avatar avatar-btn float-left mr-2" style="background-image: url({{ Storage::url($user->avatar) }})"></span>
              <span>{{ $user->name }}</span>
              @if (in_array($user->id, $adminsId))
              <div>
                <button type="button" class="btn btn-link btn-sm p-0" data-toggle="modal" data-target="#contactAdmin">
                  <small><i class="fas fa-user-cog mx-2"></i>Administrateur</small>
                </button>
              </div>
              @endif
            </div>
          </div>
          @endif
          @endforeach
        </div>
      </div>
      <hr class="hr">
      <div>
        <p>Inviter une ou plusieurs personnes par mail à rejoindre le groupe:</p>
        <form id="invitMailForm" method="POST"
          action={{ route('group.invitMember', ['groupName' => $group->name, 'userId' => auth()->user()->id]) }}
          enctype="multipart/form-data">
          @csrf
          <div id="invitForm"></div>
        </form>
      </div>
      @else
      <form id="joinDemandForm" method="POST"
        action="{{ route('group.joinDemand', ['groupName' => $group->name, 'userId' => auth()->user()->id]) }}"
        enctype="multipart/form-data">
        @csrf
        <input form="joinDemandForm" name="joinGroup" id="joinGroup" class="btn btn-success" type="submit" value="Rejoindre">
      </form>
      @endif
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="contactAdmin" tabindex="-1" role="dialog" aria-labelledby="contactAdmin" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Contacter les administrateurs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="contactAdminForm" method="POST"
            action={{ route('group.contactAdmin', ['groupName' => $group->name, 'userId' => auth()->user()->id]) }}
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="subject">Sujet</label>
              <input type="text" class="form-control" name="subject" aria-describedby="helpId" placeholder="" required>
              <small id="helpId" class="form-text text-muted"></small>
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" name="message" aria-describedby="helpId" required></textarea>
              <small id="helpId" class="form-text text-muted"></small>
            </div>
          </form>
          <p>Ce formulaire permet de contacter les administrateurs du groupe. Votre e-mail ne sera pas visible par ceux-ci, ils ne pourront pas
            vous répondre directement.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Annuler</button>
          <button type="submit" form="contactAdminForm" class="btn btn-primary btn-sm">Envoyer</button>
        </div>
      </div>
    </div>
  </div>
  @endsection