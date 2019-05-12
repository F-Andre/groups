<div class="card" id="{{ $post->id }}">
        <div class="card-header">
          <div class="d-flex flex-row justify-content-between">
            <div>
              <p class="h5">{{ $post->titre }}</p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <p class="card-text">{!! nl2br(e($post->contenu)) !!}</p>
          @if (strlen($post->image) > 1 && Storage::exists($post->image))
            <div class="mb-4 text-center">
              <img class="image-blog" src="{!! Storage::url($post->image) !!}">
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
            <p>{{ $comment->comment }}</p>
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
            <div class="form-group mt-2">
              <label for="comment">Ajouter un commentaire</label>
              <textarea class="form-control" name="comment" id="comment" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm float-right">Envoyer</button>
          </form>
          @endauth
        </div>
      </div>
