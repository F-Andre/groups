@extends('admin/admin_template')

@section('section')
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
      <a class="btn btn-warning" href="{{ route('user_page.edit', ['id' => $user->id]) }}">Editer</a>
      @endif
    </div>
  </div>
  <div class="card-body">
    @if (auth()->user()->id !== $user->id)
    <form class="form-inline mb-4" action={{ route('admin.adminSwitch', $groupName) }} method="POST" enctype="multipart/form-data">
      @csrf
      <div class="custom-control custom-switch mr-3">
        <input type="text" name="user_id" value={{ $user->id }} hidden>
        <input type="checkbox" name="admin" class="custom-control-input" id="customSwitch1" @if(in_array($user->id, $groupAdmins)) checked @endif>
        <label class="custom-control-label" for="customSwitch1">Administrateur du groupe</label>
      </div>
      <input type="submit" class="btn btn-success" value="Valider">
    </form>
    @if (!in_array($user->id, $groupAdmins))
    <div class="d-flex justify-content-start">
      <div class="text-center mr-4">
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
            <input type="text" class="form-control mr-3" name="reason" placeholder="Raisons de la radiation" required>
            <input type="submit" class="btn btn-danger" value="Radier">
          </div>
        </form>
      </div>
    </div>
    @endif
    @endif
    <hr class="hr">
    <form method="GET" action={{ route( 'admin.show', ['id'=> $user->id, 'groupName' => $groupName]) }}>
      <div class="form-group my-4 ml-4">
        <label for="tri">Trier les posts:</label>
        <select id="tri" name="tri">
          <option value="">--choix--</option>
          <option value="titre">Par titre</option>
          <option value="created-desc">Date de création (le + récent)</option>
          <option value="created-asc">Date de création (le + vieux)</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Trier</button>
      </div>
    </form>
    <table class="col-lg-11 mx-auto table table-striped table-inverse table-responsive text-nowrap">
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
        <tr>
          <td>{{ $post->titre }}</td>
          <td class="text-center">le {{ Date::parse($post->created_at)->format('d F Y') }} à
            {{ Date::parse($post->created_at)->format('H:i') }}</td>
          <td class="text-center">{{ $post->comments()->count() }}</td>
          <td class="text-right"><a class="btn btn-success btn-sm"
              href="{{ route('posts.index', ['groupName' => $groupName, '#' . $post->id]) }}">Voir l'article</a></td>
          <td class="text-right">
            <button type="button" class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#deletePost">
              Supprimer
            </button>
          </td>
        </tr>
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
@endsection