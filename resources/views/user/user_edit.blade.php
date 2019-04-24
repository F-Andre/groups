@extends('user/user_template')

@section('article')
    <article class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <p class="h4 text-center">Modifier vos informations:</p>
            </div>
            <div class="card-body">
                <script>
                    var name = {!! json_encode($user->name) !!};
                    var email = {!! json_encode($user->email) !!};
                    var notifs = {!! json_encode($user->notifs) !!}
                    var avatar = {!! json_encode($avatarUrl) !!}
                    var defaultAvatar = {!! json_encode($defaultAvatar) !!}
                </script>
                <form class="my-5" method="POST" action={{ route('user_page.update', ['id' => $user->id]) }} enctype="multipart/form-data" >
                    @method('PUT')
                    @csrf
                    <div id="userEditForm"></div>
                </form>
                <a href={{ route('user_page.index') }} type="button" class="btn btn-primary">
                    Annuler
                </a>
                <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteAccount">
                    Supprimer votre compte
                </button>
            </div>
            @if ($user->admin == 1)
                <div class="card-footer text-muted">
                    Vous êtes administrateur du site
                </div>
            @endif
        </div>
    </article>
    <div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer votre compte</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <p class="h4 my-3">ATTENTION!<br>En supprimant votre compte, tous vos articles seront aussi supprimés!</p>
                    <form method="POST" action="{{ route('user_page.destroy', ['id' => $user->id]) }}">
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
