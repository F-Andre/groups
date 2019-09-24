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
    </tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
    @if (in_array($user->id, $groupUsers))
    @php
    $postsQ = $user->userPosts()->count();
    $commentsQ = $user->userComments()->count();
    @endphp
    <tr>
      <td><a href="{{ route('admin.show', ['id' => $user->id, 'groupName' => $groupName]) }}">{{ $user->name }}</a></td>
      <td class="text-center">{{ $postsQ }}</td>
      <td class="text-center">{{ $commentsQ }}</td>
    </tr>
    @endif
    @endforeach
  </tbody>
</table>
{{ $links }}
@endsection