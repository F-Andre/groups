@extends('template')

@section('content')
<div id="loadModal"></div>
<div class="container-fluid col-xl-6 py-4">
  <div class="card">
    <div class="card-header group-bg">
      Créer un groupe
    </div>
    <div class="card-body">
      @if (session()->has('ok'))
      <div class="col-lg-6 mx-auto mt-4 alert alert-success alert-dismissible fade show" role="alert">
        {{ session('ok') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      @if (session()->has('error'))
      <div class="col-lg-6 mx-auto mt-4 alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif
      <script>
        var avatar = {!! json_encode($avatarUrl) !!};
      </script>
      <form method="POST" action="{{ route('group.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="user_id" id="user_id" value={{ auth()->user()->id }} hidden />
        <div id="groupForm"></div>
      </form>
    </div>
  </div>
</div>
@endsection