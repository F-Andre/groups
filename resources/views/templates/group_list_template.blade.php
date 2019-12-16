<div class="card m-2 flex-fill">
  <div class="card-body d-flex group-card-body">
    @if (in_array($group->id, explode(',', auth()->user()->groups_id)))
    <a id={{ $group->name }} style="display: none;" href={{ route('posts.index', $group->name) }}></a>
    @else
    <a id={{ $group->name }} style="display: none;" href={{ route('group.show', $group->name) }}></a>
    @endif
    <div class="avatar avatar-group mx-auto mx-sm-1" style="background-image: url({{ $avatarUrl }})"></div>
    <div class="ml-0 ml-sm-3 mt-2">
      <p class="card-title h5 text-center text-sm-left">{{ $group->name }}</p>
      <p class="card-text text-center text-sm-left">{{ $group->description }}</p>
    </div>
  </div>
  <div class="card-footer">
    @php
    $onDemandArray = explode(",", $group->on_demand);
    $adminsArray = explode(",", $group->admins_id);
    @endphp
    @if (count($onDemandArray) > 0 && strlen($onDemandArray[0] > 0) && in_array(auth()->user()->id, $adminsArray))
    <span class="badge badge-pill badge-danger">
      @if (count($onDemandArray) == 1)
      {{ count($onDemandArray) }} demande d'adhésion
      @else
      {{ count($onDemandArray) }} demandes d'adhésion
      @endif
    </span>
    @endif
    <a class="btn btn-outline-success btn-sm float-right" href={{ route('group.show', $group->name) }} role="button">Infos</a>
  </div>
</div>