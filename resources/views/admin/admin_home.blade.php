@extends('admin/admin_template')

@section('section')
@if (session()->has('ok'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('ok') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
{{ $links }}
<table class="table table-striped table-inverse table-responsive text-nowrap">
  <thead class="thead-dark">
    <tr>
      <th>Nom</th>
      <th>Nbre d'articles</th>
      <th>Nbre de commentaires</th>
      <th></th>
      <th></th>
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
    @endphp
    <tr>
      <td><a href="{{ route('admin.show', ['id' => $user->id, 'groupName' => $groupName]) }}">{{ $user->name }}</a></td>
      <td class="text-center">{{ $postsQ }}</td>
      <td class="text-center">{{ $commentsQ }}</td>
      <td class="text-center"><a class="btn btn-sm btn-warning" href="#" role="button">Avertir</a></td>
      <td class="text-center"><a class="btn btn-sm btn-danger" href="#" role="button">Radier</a></td>
    </tr>
    @endif
    @endforeach
  </tbody>
</table>
{{ $links }}
<div class="modal fade" id="groupEject" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center">Radier un membre du groupe</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="h4 text-center my-3"><b>ATTENTION!</b><br>En radiant un membre du groupe, tous ses articles et
          commentaires seront aussi supprimés.<br>Cette action est irréversible.</p>
        <form method="POST" action="{{ route('group.removeUser', ['groupName' => $groupName, 'id' => $user->id]) }}">
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