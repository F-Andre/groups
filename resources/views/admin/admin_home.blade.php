@extends('admin/admin_template')

@php
$groupName = $group->name;
@endphp

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
<div class="col-lg-10">
  <div class="card mb-4">
    <div class="card-header d-flex flex-wrap-reverse justify-content-between align-items-center">
      <div>
        <p class="h3">{{ $group->name }}</p>
        <p>{{ $group->description }}</p>
        <p class="card-title">Créé le: {{ $dateCreation }}</p>
      </div>
      <div>
        <span class="avatar" style={{"background-image:url(".Storage::url($group->avatar).")"}}></span>
      </div>
    </div>
    <div class="card-body d-flex justify-content-end">
      <a name="edit" id="edit" class="btn btn-warning btn-sm mr-2" href="{{ route('group.edit', $group->id) }}"
        role="button">Editer
        les infos</a>
      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteGroup">
        Supprimer le groupe
      </button>
    </div>
  </div>
</div>
<div class="col-lg-10">
  <div class="card mb-4">
    <p class="h4 card-header">Demandes d'adhésion:</p>
    <div class="card-body">
      @if (strlen($groupOnDemand[0]) > 0)
      <table class="table table-striped table-inverse table-responsive text-nowrap">
        <thead class="thead-dark">
          <tr>
            <th>Nom</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          @if (in_array($user->id, $groupOnDemand))
          <tr>
            <td>{{ $user->name }}</td>
            <td class="text-center">
              <form action={{ route('admin.joinGroup', $group->name) }} method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="user_id" value={{ $user->id }} hidden>
                <input type="text" name="join" value="true" hidden>
                <input type="submit" class="btn btn-sm btn-success" value="Accepter">
              </form>
            </td>
            <td class="text-center">
              <form action={{ route('admin.joinGroup', $group->name) }} method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="user_id" value={{ $user->id }} hidden>
                <input type="text" name="join" value="false" hidden>
                <input type="submit" class="btn btn-sm btn-danger" value="Refuser">
              </form>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
      @else
      <p class="h5 ml-5">Aucune demande pour l'instant</p>
      @endif
    </div>
  </div>
</div>
<div class="col-lg-10">
  <div class="card mb-3">
    <p class="card-header h4">Membres du groupe:</p>
    <div class="card-body">
      <table class="table table-striped table-inverse table-responsive text-nowrap">
        <thead class="thead-dark">
          <tr>
            <th>Nom</th>
            <th>Nbre d'articles</th>
            <th>Nbre de commentaires</th>
            <th>Avertissements</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          @if (in_array($user->id, $groupUsers))
          @php
          $postsUser = $posts->where('user_id', $user->id);
          $postsQ = count($postsUser);
          $commentsUser = $comments->where('user_id', $user->id);
          $commentsQ = count($commentsUser);
          $warningsQ = count(array_keys($usersWarned, $user->id));
          @endphp
          <tr>
            <td><a href={{ route('admin.show', [$group->name, $user->id]) }}>{{ $user->name }}</a></td>
            <td class="text-center">{{ $postsQ }}</td>
            <td class="text-center">{{ $commentsQ }}</td>
            <td class="text-center">{{ $warningsQ }}</td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteGroup" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title h3">Supprimer le groupe</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="h4 text-center my-3 mb-4"><b>ATTENTION!</b><br><br>En supprimant le groupe vous supprimez aussi
          définitivement les articles et les commentaires de ce groupe!<br><br>Cette action est irréversible!!</p>
        <hr class="hr">
        <form method="POST" action="{{ route('group.destroy', $group->id) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger float-right">Continuer</button>
        </form>
        <button type="button" class="btn btn-success" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>
@endsection