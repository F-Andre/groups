@extends('admin/admin_template')

@section('section')
@if (session()->has('ok'))
<div class="alert alert-success alert-dismissible fade show col-lg-8 ml-5" role="alert">
  {{ session('ok') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if (session()->has('error'))
<div class="alert alert-warning alert-dismissible fade show col-lg-8 ml-5" role="alert">
  {{ session('error') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <i class="fas fa-exclamation-triangle"></i>
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@if (in_array($user->id, $groupUsers))
<div class="card mt-4">
  <div class="card-header d-flex justify-content-between flex-wrap">
    <div>
      <h3>{{ $user->name }}</h3>
      @if ($user->admin == 1)
      <p class="card-title text-danger">Administrateur</p>
      @endif
    </div>
    <div>
      @if (Auth::user()->name == $user->name)
      <a class="btn btn-warning" href="{{ route('user_page.edit', $user->id) }}">Editer</a>
      @endif
    </div>
  </div>
  <div class="card-body">
    @if (auth()->user()->id !== $user->id)
    <div class="mb-5 mt-2">
      <form class="form-inline" action={{ route('admin.adminSwitch', $groupName) }} method="POST" enctype="multipart/form-data">
        @csrf
        <div class="custom-control custom-switch mr-3">
          <input type="text" name="user_id" value={{ $user->id }} hidden>
          <input type="checkbox" name="admin" class="custom-control-input" id="customSwitch1" @if(in_array($user->id,
          $groupAdmins)) checked @endif>
          <label class="custom-control-label" for="customSwitch1">Administrateur du groupe</label>
        </div>
        <input type="submit" class="btn btn-success btn-sm" value="Valider">
      </form>
    </div>
    @if (!in_array($user->id, $groupAdmins))
    <div class="d-flex justify-content-between my-5">
      @php
      $receiverIds = [];
      array_push($receiverIds, $user->id);
      @endphp
      <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#contactModal">
        Contacter
      </button>
      <div class="text-center mr-5">
        <form class="form-inline" action={{ route('admin.warnUser', $groupName) }} method="POST" enctype="multipart/form-data">
          @csrf
          <input type="text" name="user_id" value={{ $user->id }} hidden>
          <div class="form-group">
            <input type="text" class="form-control mr-3" name="reason" placeholder="Raisons de l'avertissement" required>
            <input type="submit" class="btn btn-warning" value="Avertir">
          </div>
        </form>
      </div>
      <div class="text-center ml-4">
        <form class="form-inline" method="POST" action={{ route('admin.deregisterUser', $groupName) }}>
          @csrf
          <input type="text" id="deregisterForm" name="user_id" value={{ $user->id }} hidden>
          <div class="form-group">
            <input type="text" class="form-control mr-3" name="reason" placeholder="Raisons de l'exclusion" required>
            <input type="submit" class="btn btn-danger" value="Exclure">
          </div>
        </form>
      </div>
    </div>
    <hr class="hr">
    @endif
    @endif
    <form class="form-inline mb-3" method="GET" action={{ route( 'admin.show', [$groupName, $user->id]) }}>
      <select id="tri" name="tri">
        <option value="">Trier les posts</option>
        <option value="titre">Par titre</option>
        <option value="created-desc">Le + récent</option>
        <option value="created-asc">Le + vieux</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm ml-2">Trier</button>
    </form>
    <table class="table table-striped table-inverse table-responsive text-nowrap">
      <thead class="thead-dark">
        <tr class="text-center">
          <th>Titre</th>
          <th>Créé le</th>
          <th>Nbr Cmt</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($posts as $post)
        @if ($post->group_id == $group->id)
        <tr>
          <td>{{ $post->titre }}</td>
          <td class="text-center">le {{ Date::parse($post->created_at)->format('d F Y') }} à
            {{ Date::parse($post->created_at)->format('H:i') }}</td>
          <td class="text-center">{{ $post->comments()->count() }}</td>
          <td class="text-right"><a class="btn btn-success btn-sm" href={{ route('posts.index', [$groupName, '#' . $post->id]) }}>Voir
              l'article</a></td>
          <td class="text-right">
            <button type="button" class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#deletePost">
              Supprimer
            </button>
          </td>
        </tr>
        @endif
        @include('templates/modal_delete_post')
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="deregisterUser" tabindex="-1" role="dialog" aria-labelledby="deregisterUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title text-center h4">Radier un membre du groupe</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="h3 text-center my-3"><b>ATTENTION!</b><br>En radiant un membre du groupe, tous ses articles et
          commentaires seront aussi supprimés.<br><br>Cette action est irréversible.</p>
        <a type="button" href={{ route('admin.deregisterUser', $groupName) }} class="btn btn-danger float-right"
          onclick="event.preventDefault(); document.getElementById('deregisterForm').submit();">Continuer</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>
@else
<script>
  window.history.back()
</script>
@endif
@include('templates/modal_contact_template')
@endsection