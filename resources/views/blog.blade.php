@extends('template')

@section('content')
<article class="mx-auto col-lg-6">
	@if (session()->has('ok'))
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		{{ session('ok') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif
	@if (count($posts) > 0)
		{{ $links }}
		@foreach ($posts as $post)
			<div class="card">
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
						<p class="card-text">{!! nl2br(e($post->contenu)) !!}</p>
                        @if (strlen($post->image) > 1 && Storage::exists($post->image))
                            <div class="mb-4 text-center">
                                <img class="image-blog" src="{!! Storage::url($post->image) !!}">
                            </div>
						@endif

						@if (Auth::check())
						<div class="container">
							<div class="col-6 float-left">
							@if (Auth::user()->id == $post->user->id)
								<a name="edit" id="edit" class="btn btn-warning btn-sm" href="{{ route('blog.edit', ['id' => $post->id]) }}" role="button">Editer l'article</a>
							@endif
							</div>
							<div class="col-6 float-right text-right">
							@if (Auth::user()->admin or Auth::user()->id == $post->user->id)
								<form method="POST" action="{{ route('blog.destroy', ['id' => $post->id]) }}">
									@method('DELETE')
									@csrf
									<button type="submit" class="btn btn-danger btn-sm">Effacer l'article</button>
								</form>
							@endif
							</div>
						</div>
						@endif
					</div>
					<div class="card-footer">
                        @foreach ($post->comments()->get() as $comment)
                            <div class="comment">
                                <small class="d-block text-right">Commentaire de {{ $comment->user->name }}
                                    @if (time() - $comment->created_at->timestamp < 172800)
                                        {{ Date::parse($comment->created_at)->diffForHumans() }}
                                    @else
                                        le {{ Date::parse($comment->created_at)->format('l d F Y') }} à {{ Date::parse($comment->created_at)->format('H:i') }}
                                    @endif
                                </small>
                                <hr>
                                <p>{{ $comment->comment }}</p>
                                <div class="text-right">
                                    @if (Auth::user()->admin or Auth::user()->id == $comment->user->id)
                                        <form method="POST" action="{{ route('comment.destroy', ['id' => $comment->id]) }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Effacer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
						<form method="POST" action="{{ route('comment.store', ['post_id' => $post->id, 'user_id' => Auth::user()->id]) }}">
                            @csrf
                            <div class="form-group mt-2">
                              <label for="comment">Ajouter un commentaire</label>
                              <textarea class="form-control" name="comment" id="comment" rows="2"></textarea>
                            </div>
							<button type="submit" class="btn btn-primary btn-sm float-right">Envoyer</button>
						</form>
					</div>
			</div>
			<br>
			<?php unset($date); ?>
		@endforeach
		{{ $links }}
	@else
		<div class="mt-5 text-center">
			<p class="h2">Il n'y encore aucun article</p>
			<p class="h3">Lancez-vous!</p>
			<a class="btn btn-outline-success mt-5" href="{{ route('blog.create') }}" role="button">Ecrire un article</a>
		</div>
	@endif
</article>
@endsection
