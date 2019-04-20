@extends('user/user_template')
@section('article')
<article class="col-lg-6 mx-auto">
  @if (count($posts) > 0) @foreach ($posts as $post)
  @include('card_template')
  @endforeach @else
  <div class="text-center mt-5">
    <p class="h4 justify-content-center mt-5">Vous n'avez aucun article!</p>
  </div>
  @endif
</article>
@endsection
