@extends('template')

@section('content')
<div class="container-fluid col-lg-6 bx-auto py-4">
  @if (session()->has('ok'))
  <div class="alert alert-success alert-dismissible fade show col-lg-8 ml-5" role="alert">
    {{ session('ok') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  @if (session()->has('error'))
  <div class="alert alert-warning alert-dismissible fade show col-lg-8 ml-5" role="alert">
      <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="card">
    <div class="card-header group-bg d-flex justify-content-between">
      <div>Editer un article</div>
      <div>
        <a class="btn btn-sm btn-danger" href={{ route('posts.index', $groupName) }} role="button">Annuler</a>
      </div>
    </div>
    <div class="card-body">
      <script>
        var titre = {!! json_encode($titre) !!};
        var contenu = {!! json_encode($contenu) !!};
        var image = {!! json_encode($imageUrl) !!};
      </script>
      <form method="POST" action={{ route('posts.update', [$groupName, $id]) }} enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="postEditForm"></div>
      </form>
    </div>
  </div>
</div>
@endsection