@extends('user/user_template')
@section('article')
<article class="col-lg-8 mx-auto">
  @if (count($posts) > 0)
  <form method="GET" action={{ route( 'user_page.show', [ 'id'=> $user->id]) }}>
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
  <table class="table table-hover table-responsive-md table-posts">
    <thead class="thead-dark">
      <tr>
        <th>Titre</th>
        <th>Crée le:</th>
        <th>Modifié le:</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    @foreach ($posts as $post)
    <tbody>
      <tr>
        <td scope="row">{{ $post->titre }}</td>
        <td>{{ Date::parse($post->created_at)->format('d M Y') }} à {{ Date::parse($post->created_at)->format('H:i') }}</td>
        <td>{{ Date::parse($post->updated_at)->format('d M Y') }} à {{ Date::parse($post->updated_at)->format('H:i') }}</td>
        <td><a name="edit" id="edit" class="btn btn-warning btn-sm" href="{{ route('blog.edit', ['id' => $post->id]) }}" role="button">Editer l'article</a></td>
        <td>
          <form method="POST" action="{{ route('blog.destroy', ['id' => $post->id]) }}">
            @method('DELETE') @csrf
            <button type="submit" class="btn btn-danger btn-sm">Effacer l'article</button>
          </form>
        </td>
      </tr>
    </tbody>
    @endforeach
  </table>
  @else
  <div class="text-center mt-5">
    <p class="h4 justify-content-center mt-5">Vous n'avez aucun article!</p>
  </div>
  @endif
</article>
@endsection
