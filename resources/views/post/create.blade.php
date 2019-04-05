@extends('template')

@section('content')
    <div id="loadModal"></div>
    <div class="container-fluid col-lg-6 bx-auto">
        <div class="card">
            <div class="card-header">
                Créer un post
            </div>
            <div class="card-body">
                <form  method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div id="articleForm"></div>
                </form>
            </div>
        </div>
    </div>
@endsection
