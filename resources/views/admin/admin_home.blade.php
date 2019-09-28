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
@if (session()->has('error'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  {{ session('error') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <i class="fas fa-exclamation-triangle"></i>
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
<p class="h4">Demandes d'adhésion:</p>
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
        <form action={{ route('admin.joinGroup', $groupName) }} method="POST" enctype="multipart/form-data">
          @csrf
          <input type="text" name="user_id" value={{ $user->id }} hidden>
          <input type="text" name="join" value="true" hidden>
          <input type="submit" class="btn btn-sm btn-success" value="Accepter">
        </form>
      </td>
      <td class="text-center">
        <form action={{ route('admin.joinGroup', $groupName) }} method="POST" enctype="multipart/form-data">
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
<hr class="mt-5">
<p class="h4 mb-3">Membres du groupe:</p>
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
      <td><a href={{ route('admin.show', [$groupName, $user->id]) }}>{{ $user->name }}</a></td>
      <td class="text-center">{{ $postsQ }}</td>
      <td class="text-center">{{ $commentsQ }}</td>
      <td class="text-center">{{ $warningsQ }}</td>
    </tr>
    @endif
    @endforeach
  </tbody>
</table>
@endsection