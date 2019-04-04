@extends('template')

@section('content')
<div class="container-fluid col-6 bx-auto">
    <div class="card">
        <div class="card-header">
            Edition d'un post
        </div>
        <div class="card-body">
            <script>
                var titre = {!! json_encode($titre) !!};
                var contenu = {!! json_encode($contenu) !!};
                var image = {!! json_encode($imageUrl) !!}
            </script>
            <form  method="POST" action="{{ route('blog.update', ['id' => $id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div id="postEditForm"></div>
            </form>
        </div>
    </div>
</div>
@endsection
