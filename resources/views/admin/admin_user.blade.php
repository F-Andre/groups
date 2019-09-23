@extends('admin/admin_template')

@section('section')
<div class="card mt-4">
  <div class="card-header d-flex justify-content-between flex-wrap">
    <div>
      <h3>{{ $user->name }}</h3>
      <p class="card-title">{{ $user->email }}</p>
      @if ($user->admin == 1)
      <p class="card-title text-danger">Administrateur</p>
      @endif
    </div>
    <div>
      @if (Auth::user()->name != $user->name)
      <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteAccount">
        Supprimer le compte
      </button>
      @endif
      @if (Auth::user()->name == $user->name)
        <a class="btn btn-warning" href="{{ route('user_page.edit', ['id' => $user->id]) }}">Editer</a>
      @endif
    </div>
  </div>
  <form method="GET" action={{ route( 'admin.show', ['id'=> $user->id]) }}>
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
        <td class="text-right"><a class="btn btn-success btn-sm" href="{{ route('posts.index', '#'.$post->id) }}">Voir l'article</a></td>
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
@include('templates/modal_delete_user')
@endsection
