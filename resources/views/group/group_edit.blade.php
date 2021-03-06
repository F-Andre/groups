@extends('template')

@section('content')
@if (in_array(auth()->user()->id, $adminsId))
<div id="loadModal"></div>
<div class="container-fluid col-xl-6 py-4">
  <div class="card">
    <div class="card-header group-bg">
      Edition du groupe "{{ $group->name }}"
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
        var name = {!! json_encode($group->name) !!};
        var desc = {!! json_encode($group->description) !!};
        var avatar = {!! json_encode($avatarUrl) !!};
        var defaultAvatar = {!! json_encode($defaultAvatar) !!};
        var masked = {!! json_encode($group->masked) !!}
        masked == 'true' ? masked = 1 : masked = 0;
      </script>
      <form method="POST" action="{{ route('group.update', $group->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="groupEditForm"></div>
      </form>
    </div>
  </div>
</div>  
@else
  <script>window.history.back();</script>  
@endif
@endsection