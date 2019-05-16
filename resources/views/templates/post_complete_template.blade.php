<div class="card" id="{{ $post->id }}">
  <div class="card-header">
    <div class="d-flex flex-row justify-content-between">
      <div>
        <p class="h5">{{ $post->titre }}</p>
        <small>
          Par <b>{{ $post->user->name }}</b>
          @if (time() - $post->created_at->timestamp < 172800)
            {{ Date::parse($post->created_at)->diffForHumans() }}
            @if ($post->created_at != $post->updated_at)
              @if (time() - $post->updated_at->timestamp < 172800)
                | Modifié {{ Date::parse($post->updated_at)->diffForHumans() }}
              @else
                | Modifié le: {{ Date::parse($post->updated_at)->format('d F Y') }} à {{ Date::parse($post->updated_at)->format('H:i') }}
            @endif
          @endif
          @else
            le {{ Date::parse($post->created_at)->format('l d F Y') }} à {{ Date::parse($post->created_at)->format('H:i') }}
            @if ($post->created_at != $post->updated_at)
              @if (time() - $post->updated_at->timestamp < 172800)
                | Modifié {{ Date::parse($post->updated_at)->diffForHumans() }}
              @else
                | Modifié le {{ Date::parse($post->updated_at)->format('d F Y') }} à {{ Date::parse($post->updated_at)->format('H:i')}}
              @endif
            @endif
          @endif
        </small>
      </div>
      <div>
        <img class="avatar avatar_icon" src="{{ Storage::url(DB::table('users')->where('id', $post->user_id)->first()->avatar) }}" />
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="card-text">{!! $post->contenu !!}</div>
    @if (strlen($post->image) > 1 && Storage::exists($post->image))
      <div class="mb-4 text-center">
        <img class="image-blog" src="{!! Storage::url($post->image) !!}">
      </div>
    @endif
    @if (Auth::check())
      <div class="container">
        <div class="col-6 float-right text-right">
        @if (Auth::user()->admin or Auth::user()->id == $post->user->id)
        <div class="dropdown">
          <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @if (Auth::user()->id == $post->user->id)
              <a name="edit" id="edit" class="dropdown-item" href="{{ route('blog.edit', ['id' => $post->id]) }}" role="button">Editer l'article</a>
            @endif
            <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#deletePost">
              Supprimer l'article
            </button>
          </div>
        </div>
        @include('templates/modal_delete_post')
        @endif
        </div>
      </div>
    @endif
  </div>
  <div class="card-footer">
    @foreach ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
    <div class="comment">
      <img class="avatar avatar-cmt"
        src="{{ Storage::url(DB::table('users')->where('id', $comment->user_id)->first()->avatar) }}" />
      <small class="d-block col-8 float-right text-right">Commentaire de {{ $comment->user->name }}
        @if (time() - $comment->created_at->timestamp < 172800)
          {{ Date::parse($comment->created_at)->diffForHumans() }}
        @else
          le {{ Date::parse($comment->created_at)->format('l d F Y') }} à {{ Date::parse($comment->created_at)->format('H:i') }}
        @endif
      </small>
      <hr>
      <p>{!! $comment->comment !!}</p>
      <div class="text-right">
        @auth
          @if (Auth::user()->admin or Auth::user()->id == $comment->user->id)
          <form method="POST" action="{{ route('comment.destroy', ['id' => $comment->id]) }}">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Effacer</button>
          </form>
          @endif
        @endauth
      </div>
    </div>
    @endforeach
    @auth
    <form method="POST" action="{{ route('comment.store', ['post_id' => $post->id, 'user_id' => Auth::user()->id]) }}">
      @csrf
      <div id="commentForm"></div>
    </form>
    @endauth
  </div>
</div>
