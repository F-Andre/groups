<div class="card flex-fill mb-2">
  <div class="card-body d-flex card-group">
    @if (in_array(auth()->user()->id, $userArray))
    <a id={{ $group->name }} style="display: none;" href={{ route('posts.index', $group->name) }}></a>
    @else
    <a id={{ $group->name }} style="display: none;" href={{ route('group.show', $group->name) }}></a>
    @endif
    <div>
      <img src={{ $avatarUrl }} class="avatar avatar-group" alt="{{ $group->name }}-image">
    </div>
    <div class="ml-3 mt-2">
      <p class="card-title h5">{{ $group->name }}</p>
      <p class="card-text">{{ $group->description }}</p>
    </div>
  </div>
  <div class="card-footer">
    <a class="btn btn-outline-success btn-sm float-right" href={{ route('group.show', $group->name) }} role="button">Infos</a>
  </div>
</div>