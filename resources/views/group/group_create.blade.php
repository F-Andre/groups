@extends('template')

@section('content')
<div id="loadModal"></div>
<div class="container-fluid col-lg-6 bx-auto py-4">
  <div class="card">
    <div class="card-header">
      Créer un groupe
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('group.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="user_id" id="user_id" value={{ auth()->user()->id }} hidden />
        <div id="groupForm"></div>
      </form>
    </div>
  </div>
</div>
@endsection
