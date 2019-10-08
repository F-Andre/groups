@extends('user/user_template')

@section('article')
@if ($user->id == Auth::user()->id)
  @if (session()->has('error'))
  <div class="col-lg-6 mx-auto mt-4 alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <article class="col-lg-8 mx-auto mt-4">
    <div class="card">
      <div class="card-header">
        <p class="h4 text-center">Modifier vos informations:</p>
      </div>
      <div class="card-body">
        <script>
          var name = {!! json_encode($user->name) !!};
          var email = {!! json_encode($user->email) !!};
          var notifs = {!! json_encode($user->notifs) !!};
          var avatar = {!! json_encode($avatarUrl) !!};
          var defaultAvatar = {!! json_encode($defaultAvatar) !!};
        </script>
        <form class="my-5" method="POST" action={{ route('user_page.update', $user->id) }}
          enctype="multipart/form-data">
          @method('PUT')
          @csrf
          <div id="userEditForm"></div>
        </form>
        <div class="d-flex justify-content-sm-between justify-content-center flex-wrap">
          <div class="mb-2 mr-1">
            <a href={{ route('user_page.index') }} type="button" class="btn btn-primary">
              Annuler
            </a>
          </div>
          <div class="mb-2 ml-1">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAccount">
              Supprimer votre compte
            </button>
          </div>
        </div>
      </div>
    </div>
  </article>
  @include('templates/modal_delete_user')
@else
<script>window.history.back(); </script>
@endif
@endsection
