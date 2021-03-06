<div class="card" id="{{ $post->id }}">
  <div class="card-header group-bg">
    <div class="d-flex flex-row justify-content-between">
      <div>
        <p class="h4">{{ $post->titre }}</p>
      </div>
    </div>
  </div>
  <div class="card-body">
    @php
        $imageUrl = Storage::url($post->image);
    @endphp
    <p class="card-text">{!! $post->contenu !!}</p>
    @if (strlen($post->image) > 1 && $imageUrl)
      <div class="mb-4 text-center">
        <img class="image-blog" src="{!! $imageUrl !!}">
      </div>
    @endif
    <div class="float-right">
      <a name="edit" id="edit" class="btn btn-warning btn-sm" href={{ route('posts.edit', [$groupName, $post->id]) }} role="button">Editer l'article</a>
    </div>
  </div>
  <div class="card-footer">
    <?php $comments = $post->comments()->orderBy('created_at', 'desc')->get(); ?>
    @foreach ($comments as $comment)
    <div class="comment">
      <img class="avatar avatar-cmt"
        src="{{ Storage::url(DB::table('users')->where('id', $comment->user_id)->first()->avatar) }}" />
      <small class="d-block col-8 float-right text-right">Commentaire de {{ $comment->user->name }}
        @if (time() - $comment->created_at->timestamp < 172800)
          {!! \Carbon\Carbon::parse($comment->created_at)->locale('fr')->diffForHumans() !!}
        @else
          le {!! \Carbon\Carbon::parse($comment->created_at)->locale('fr')->isoFormat('dddd D MMMM YYYY \à HH:mm') !!}
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
      <div class="form-group mt-2">
        <label for="comment">Ajouter un commentaire</label>
        <textarea class="form-control" name="comment" id="comment" rows="2"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-sm float-right">Envoyer</button>
    </form>
    @endauth
  </div>
</div>
