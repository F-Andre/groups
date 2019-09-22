@extends('admin/admin_template')

@section('section')
@if (session()->has('ok'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  {{ session('ok') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
<form method="GET" action={{ route( 'admin.index' ) }}>
    <div class="form-group my-4">
      <label for="tri">Trier les utilisateurs:</label>
      <select id="tri" name="tri">
        <option value="">--choix--</option>
        <option value="nom">Par nom</option>
        <option value="email">Par e-mail</option>
        <option value="admin">Admin</option>
        <option value="created-asc">Date de creation</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Trier</button>
    </div>
  </form>
{{ $links }}
<table class="table table-striped table-inverse table-responsive text-nowrap">
  <thead class="thead-dark">
    <tr>
      <th>Nom</th>
      <th>e-mail</th>
      <th>Nbre d'articles</th>
      <th>Nbre de commentaires</th>
      <th>Admin</th>
      <th>Date de création</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
    <?php
      $postsQ = $user->userPosts()->count();
      $commentsQ = $user->userComments()->count();
    ?>
    <tr>
      <td><a href="{{ route('admin.show', [$user]) }}">{{ $user->name }}</a></td>
      <td>{{ $user->email }}</td>
      <td class="text-center">{{ $postsQ }}</td>
      <td class="text-center">{{ $commentsQ }}</td>
      <td class="text-center">
        @if ($user->admin == 1)
        Oui
        @else
        Non
        @endif
      </td>
      <td>{{ Date::parse($user->created_at)->format('d F Y') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $links }}
@endsection
