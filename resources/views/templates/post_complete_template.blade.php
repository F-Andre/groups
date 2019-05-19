<div class="card post" id="{{ $post->id }}">
  <div class="card-header">
    <div class="d-flex flex-row justify-content-between">
      <div>
        <p class="h4">{{ $post->titre }}</p>
        <small>
          Par <b>{{ $post->user->name }}</b>
          @if (time() - $post->created_at->timestamp < 172800) {{ Date::parse($post->created_at)->diffForHumans() }}
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
              |  Modifié {{ Date::parse($post->updated_at)->diffForHumans() }}
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
      <div class="float-right text-right">
        @if (Auth::user()->admin or Auth::user()->id == $post->user->id)
        <div class="dropdown">
          <button class="btn btn-outline-light btn-sm dropdown-toggle edit-button text-secondary" type="button"
            id="dropdownPost{{ $post->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownPost{{ $post->id }}">
            @if (Auth::user()->id == $post->user->id)
            <a name="edit" id="edit" class="dropdown-item" href="{{ route('blog.edit', ['id' => $post->id]) }}"
              role="button">Editer l'article</a>
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
    <p><b>Commentaires:</b></p>
    <?php $comments = $post->comments()->orderBy('created_at', 'desc')->get(); ?>
    @foreach ($comments as $comment)
    <div class="comment">
      <div class="comment-head d-flex justify-content-between border-bottom">
        <img class="avatar avatar-cmt mr-3"
          src="{{ Storage::url(DB::table('users')->where('id', $comment->user_id)->first()->avatar) }}" />
        <small class="align-self-end">
          <b>{{ $comment->user->name }}</b>
          @if (time() - $comment->created_at->timestamp < 172800)
            {{ Date::parse($comment->created_at)->diffForHumans() }}
          @else
            le {{ Date::parse($comment->created_at)->format('l d F Y') }} à {{ Date::parse($comment->created_at)->format('H:i') }}
          @endif
        </small>
      </div>
      <div class="my-3">
        {!! $comment->comment !!}
      </div>
      <div class="text-right">
        @auth
        @if (Auth::user()->admin or Auth::user()->id == $comment->user->id)
        <div class="dropdown">
          <button class="btn btn-outline-light btn-sm dropdown-toggle edit-button text-secondary" type="button"
            id="dropdownComment{{ $comment->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownComment{{ $comment->id }}">
            <form method="POST" action="{{ route('comment.destroy', ['id' => $comment->id]) }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="dropdown-item">Effacer</button>
            </form>
          </div>
        </div>
        @endif
        @endauth
      </div>
    </div>
    @endforeach
    @auth
    <form method="POST" action="{{ route('comment.store', ['post_id' => $post->id, 'user_id' => Auth::user()->id]) }}">
      @csrf
      <div class="commentForm"></div>
    </form>
    @endauth
  </div>
</div>
