@extends('admin/admin_template')

@section('section')
<div class="card mt-4">
    <div class="card-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10">
                    <h3>{{ $user->name }}</h3>
                    <p class="card-title">{{ $user->email }}</p>
                    @if ($user->admin == 1)
                    <p class="card-title">Administrateur</p>
                    @endif
                </div>
                <div class="col-2">
                    @if (Auth::user()->name != $user->name)
                        <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteAccount">
                            Supprimer
                        </button>
                    @endif
                    @if (Auth::user()->name == $user->name)
                        <form method="POST" action="{{ route('admin.edit', ['user']) }}">
                            @csrf
                            <button type="submit" class="btn btn-warning mt-5 float-right">Editer</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @foreach ($posts as $post)
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    <p class="h5 card-title">{{ $post->titre }}</p>
                </div>
                <div class="card-body">
                    <p class="card-texte">{{ $post->contenu }}</p>
                    @if (strlen($post->image) > 0)
                        <img src = "{{ Storage::url($post->image) }}"/>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
<!-- Modal -->
<div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer un compte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <p class="h4 my-3">ATTENTION!<br>En supprimant cet utilisateur, tous ses articles seront aussi supprimés!</p>
                <form method="POST" action="{{ route('admin.destroy', ['id' => $user->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger float-right">Continuer</button>
                </form>
                <button type="button" class="btn btn-success" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
@endsection
