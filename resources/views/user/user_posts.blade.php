@extends('user/user_template')

@section('article')
    <article class="col-lg-6 mx-auto">
        @if (count($posts) > 0)
            @foreach ($posts as $post)
            <div class="card">
                <div class="card-header">
                    <p class="h3">{{ $post->titre }}</p>
                    <small> Ecrit le {{ Date::parse($post->created_at)->format('l d F Y') }} à {{ Date::parse($post->created_at)->format('H:i') }}
                        @if ($post->created_at != $post->updated_at)
                            @if (time() - $post->updated_at->timestamp < 172800)
                                | Modifié {{ Date::parse($post->updated_at)->diffForHumans() }}
                            @else
                                | Modifié le {{ Date::parse($post->updated_at)->format('d F Y') }} à {{ Date::parse($post->updated_at)->format('H:i')}}
                            @endif
                        @endif
                    </small>
                </div>
                <div class="card-body">
                    <p class="card-text">{!! nl2br(e($post->contenu)) !!}</p>

                    @if (strlen($post->image) > 1 && Storage::exists($post->image))
                        <img class="col-12 mb-4" src="{!! Storage::url($post->image) !!}">
                    @endif
                </div>
                <div class="card-footer text-muted">
                    <div class="container">
                        <a name="" id="" class="btn btn-warning float-left" href="{{ route('blog.edit', ['id' => $post->id]) }}" role="button">Modifier l'article</a>
                        <form method="POST" action="{{ route('blog.destroy', ['id' => $post->id]) }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger float-right">Suppimer l'article</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="text-center mt-5">
            <p class="h4 justify-content-center mt-5">Vous n'avez aucun article!</p>
        </div>
        @endif
    </article>
@endsection
