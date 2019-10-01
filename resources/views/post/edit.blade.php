@extends('template')

@section('content')
<div class="container-fluid col-lg-6 bx-auto py-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
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
