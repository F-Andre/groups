<div class="text-center">
  <a class="btn btn-outline-secondary" href="{{ route('group.index') }}">
    <i class="fas fa-home mr-2"></i>Accueil
  </a>
</div>
<div class="accordion text-center mt-4" id="authAcc">
  <a class="btn btn-secondary d-flex justify-content-center align-items-center flex-wrap mx-auto" id="headingAuth" href="#" role="button" data-toggle="collapse"
    data-target="#authDropdown" aria-expanded="true" aria-controls="authDropdown">
    <span class="avatar avatar-btn mx-2"
      style="background-image: url({{ Storage::url(Auth::user()->avatar) }})"></span>
    {{ Auth::user()->name }}
    <i class="ml-2 fas fa-caret-down"></i>
  </a>
  <div id="authDropdown" class="collapse mt-2 text-left" aria-labelledby="headingAuth" data-parent="#authAcc">
    <a class="dropdown-item" href="{{ route('user_page.index') }}">
      Mon compte
    </a>
    <a class="dropdown-item" href="{{ route('logout') }}"
      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      Se déconnecter
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </div>
</div>
<hr>
<div class="accordion text-center" id="groupAcc">
  <a id="headingGroup" class="btn btn-outline-success d-flex justify-content-center align-items-center flex-wrap mx-auto" href="#" role="button" data-toggle="collapse"
    data-target="#groupDropdown" aria-expanded="true" aria-controls="groupDropdown">
    <span class="avatar avatar-btn mx-2" style="background-image: url({{ Storage::url($group->avatar) }})"></span>
    {{ $groupName }}
    <i class="ml-2 fas fa-caret-down"></i>
  </a>
  <div id="groupDropdown" class="collapse mt-2 text-left" aria-labelledby="headingGroup" data-parent="#groupAcc">
    <a class="dropdown-item" href="{{ route('posts.index', $groupName) }}">
      Fil du groupe
    </a>
    <a class="dropdown-item" href="{{ route('group.show', $groupName) }}">
      Infos du groupe
    </a>
    @if (in_array(auth()->user()->id, $groupAdmins))
    <a class="dropdown-item" href="{{ route('admin.index', $groupName) }}" role="button">Page admin</a>
    @endif
  </div>
</div>
<div class="mt-4 text-center">
  <a class="btn btn-outline-primary" href={{ route('posts.create', $groupName) }} role="button">Ecrire un
    article</a>
</div>