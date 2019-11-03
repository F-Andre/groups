@extends('template')

@section('content')
<aside class="col-2 ml-5 py-4">
  @include('templates/nav_left_template')
  @if (in_array(auth()->user()->id, $groupAdmins) && count($groupOnDemand) > 0 && strlen($groupOnDemand[0] > 0))
  <p class="mt-3 mx-0 px-0 text-center"><small>{{ count($groupOnDemand) }} demande(s) d'adhésion en attente</small></p>
  @endif
</aside>
<article class="offset-xl-3 col-xl-6 py-4">
  @if (session()->has('ok'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (session()->has('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (count($posts) > 0 && $nbrPosts > 0)
  @foreach ($posts as $post)
  @if ($post->group_id == $group->id)
  @include('templates/post_complete_template')
  @endif
  @endforeach
  @else
  <div class="mt-5 text-center">
    <p class="h2">Il n'y encore aucun article</p>
    <p class="h3">Lancez-vous!</p>
    <a class="btn btn-outline-success mt-5" href="{{ route('posts.create', $groupName) }}" role="button">Ecrire un
      article</a>
  </div>
  @endif
</article>
@endsection