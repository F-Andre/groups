<div class="card post mb-5" id="{{ $post->id }}">
  <div class="card-header group-bg">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <div>
        <p class="h4 post-titre">{{ $post->titre }}</p>
        <small>
          Par <b>{{ $post->user->name }}</b>
          @if (time() - $post->created_at->timestamp < 172800) {!! \Carbon\Carbon::parse($post->created_at)->locale('fr')->diffForHumans() !!}
          @if ($post->created_at !== $post->updated_at)
            @if (time() - $post->updated_at->timestamp < 172800) | Modifié {!! \Carbon\Carbon::parse($post->updated_at)->locale('fr')->diffForHumans() !!}
            @else | Modifié le: {!! \Carbon\Carbon::parse($post->updated_at)->locale('fr')->isoFormat('dddd D MMMM YYYY \à HH:mm') !!}
            @endif
          @endif
          @else le {!! \Carbon\Carbon::parse($post->created_at)->locale('fr')->isoFormat('dddd D MMMM YYYY \à HH:mm') !!}
          @if ($post->created_at !== $post->updated_at)
            @if (time() - $post->updated_at->timestamp < 172800) | Modifié {!! \Carbon\Carbon::parse($post->updated_at)->locale('fr')->diffForHumans() !!}
            @else | Modifié le {!! \Carbon\Carbon::parse($post->updated_at)->locale('fr')->isoFormat('dddd D MMMM YYYY \à HH:mm') !!}
            @endif
          @endif
          @endif
        </small>
      </div>
      <div>
        <span class="avatar avatar_icon" style="background-image: url({{ Storage::url(DB::table('users')->where('id', $post->user_id)->first()->avatar) }})"></span>
      </div>
    </div>
  </div>
  <div class="card-body">
    @php
      $imageUrl = Storage::url($post->image);
    @endphp
    <div class="card-text post-text">
      <p>{!! $post->contenu !!}</p>
    </div>
    @if (strlen($post->image) > 1 && $imageUrl)
      <div class="card-text my-4 text-center">
        <img class="image-blog" data-src="{!! $imageUrl !!}">
      </div>
    @endif
    @if (Auth::check())
      <div class="container">
        <div class="float-right text-right">
          @if (in_array(auth()->user()->id, $groupAdmins) or Auth::user()->id == $post->user->id)
            <div class="dropdown">
              <button class="btn btn-outline-light btn-sm dropdown-toggle edit-button text-secondary" type="button"
                      id="dropdownPost{{ $post->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownPost{{ $post->id }}">
                @if (Auth::user()->id == $post->user->id)
                  <a name="edit" id="edit" class="dropdown-item" href={{ route('posts.edit', [$groupName, $post->id]) }} role="button">Editer
                    l'article</a>
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
    <p><small><b>Commentaires:</b></small></p>
    @php
      $comments = $post->comments()->orderBy('created_at', 'asc')->get();
    @endphp
    @if (count($comments) == 0)
      <p><small>Aucun commentaire pour l'instant.</small></p>
      <hr class="hr">
    @else
      <div class="comments-div">
        @foreach ($comments as $comment)
          <div class="comment">
            <div class="comment-head d-flex justify-content-between pb-1 border-bottom">
          <span class="avatar avatar-cmt align-self-center mr-1"
                style="background-image: url({{ Storage::url(DB::table('users')->where('id', $comment->user_id)->first()->avatar) }})"></span>
              <small class="align-self-end">
                <b>{{ $comment->user->name }}</b>
                @if (time() - $comment->created_at->timestamp < 172800) {!! \Carbon\Carbon::parse($$comment->created_at)->locale('fr')->diffForHumans() !!}
                @else le {!! \Carbon\Carbon::parse($comment->created_at)->locale('fr')->isoFormat('dddd D MMMM YYYY \à HH:mm') !!}
                @endif
              </small>
            </div>
            <div class="my-3">
              {!! $comment->comment !!}
            </div>
            <div class="text-right">
              @auth
                @if (in_array(auth()->user()->id, $groupAdmins) or Auth::user()->id == $comment->user->id)
                  <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle edit-button text-secondary" type="button"
                            id="dropdownComment{{ $comment->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left" aria-labelledby="dropdownComment{{ $comment->id }}">
                      <form method="POST" action="{{ route('comment.destroy', $comment->id) }}">
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
      </div>
    @endif
    <div class="accordion" id="accordionComment{{ $post->id }}">
      <div>
        <div id="heading{{ $post->id }}">
          <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse{{ $post->id }}"
                  aria-expanded="false" aria-controls="collapse{{ $post->id }}">
            Ajouter un commentaire
          </button>
        </div>
        <div id="collapse{{ $post->id }}" class="collapse" aria-labelledby="heading{{ $post->id }}"
             data-parent="#accordionComment{{ $post->id }}">
          <div>
            <form method="POST" action="{{ route('comment.store') }}" enctype="multipart/form-data">
              @csrf
              <input type="text" name="post_id" value={{ $post->id }} hidden>
              <input type="text" name="user_id" value={{ auth()->user()->id }} hidden>
              <div class="commentForm"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>