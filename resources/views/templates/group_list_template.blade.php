<div class="card flex-fill mb-2">
  <div class="card-body d-flex card-group">
    <a id={{ $group->name }} href={{ route('group.show', $group->name) }}></a>
    <div class="mr-4">
      <img src={{ $avatarUrl }} class="avatar avatar-group" alt="{{ $group->name }}-image">
    </div>
    <div>
      <p class="card-title h5">{{ $group->name }}</p>
      <p class="card-text">{{ $group->description }}</p>
    </div>
  </div>
  <div class="card-footer">
    <a class="btn btn-success btn-sm float-right" href={{ route('group.show', $group->name) }} role="button">Infos</a>
  </div>
</div>