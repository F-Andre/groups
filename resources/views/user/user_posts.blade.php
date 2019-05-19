@extends('user/user_template')
@section('article')
<article class="col-lg-8 mx-auto">
  @if (count($posts) > 0)
  <form method="GET" action={{ route( 'user_page.show', ['id'=> $user->id]) }}>
    <div class="form-group my-4">
      <label for="tri">Trier les posts:</label>
      <select id="tri" name="tri">
        <option value="">--choix--</option>
        <option value="titre">Par titre</option>
        <option value="created-desc">Date de création (le + récent)</option>
        <option value="created-asc">Date de création (le + vieux)</option>
        <option value="updated-desc">Date de modif (le + récent)</option>
        <option value="updated-asc">Date de modif (le + vieux)</option>
      </select>
      <button type="submit" class="btn btn-primary">Trier</button>
    </div>
  </form>
  <table class="table table-hover table-responsive table-posts mb-5">
    <thead class="thead-dark">
      <tr>
        <th>Titre</th>
        <th>Crée le:</th>
        <th>Modifié le:</th>
        <th>Commentaires:</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach ($posts as $post)
      <tr>
        <td scope="row">{{ $post->titre }}</td>
        <td>{{ Date::parse($post->created_at)->format('d M Y') }} à {{ Date::parse($post->created_at)->format('H:i') }}
        </td>
        <td>{{ Date::parse($post->updated_at)->format('d M Y') }} à {{ Date::parse($post->updated_at)->format('H:i') }}
        </td>
        <td class="text-center">{{ $post->comments()->count() }}</td>
        <td>
          <form method="GET" action={{ route( 'user_page.show', ['id'=> $user->id]) }}>
            <input type="text" name="post-view" value={{ $post->id }} hidden>
            <input type="submit" class="btn btn-success btn-sm" value="Voir l'article">
          </form>
        </td>
        <td>
          <button type="button" class="btn btn-danger btn-sm float-right" data-toggle="modal" data-target="#deletePost">
            Supprimer l'article
          </button>
        </td>
      </tr>
      @include('templates/modal_delete_post')
    @endforeach
  </tbody>
  </table>
  @else
  <div class="text-center mt-5">
    <p class="h4 justify-content-center mt-5">Vous n'avez aucun article!</p>
  </div>
  @endif
  @isset($postView)
  <div class="col-lg-8 mx-auto">
    @include('templates/post_light_template', ['post' => $postView])
  </div>
  @endisset
</article>
@endsection
