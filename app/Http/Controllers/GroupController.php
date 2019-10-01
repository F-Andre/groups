<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use App\Repositories\GroupRepository;
use App\Http\Requests\GroupSearchRequest;
use App\Notifications\JoinGroupDemand;
use App\Post;
use App\User;

class GroupController extends Controller
{

  protected $group;

  public function __construct(GroupRepository $group)
  {
    $this->middleware('auth');
    $this->group = $group;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $groups = $this->group->getCollection();
    return view('group.group_index', compact('groups'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('group.group_create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if (preg_match('/[a-zA-Z0-9._-]*/', $request->name) == false) {
      return redirect(route('groupe.create'))->with('error', 'Le nom du groupe n\'est pas correct');
    }

    $inputs = array_merge($request->all(), ['users_id' => $request->user_id, 'admins_id' => $request->user_id, 'active_at' => now()]);
    $this->group->store($inputs);

    return redirect(route('group.index'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($name)
  {
    $group = Group::where('name', $name)->first();
    $usersId = explode(",", $group->users_id);

    return view('group.group_show', compact('group', 'usersId'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $group = $this->group->getById($id);

    $posts = Post::where('group_id', $id);

    foreach ($posts as $post) {

      $comments = Comment::where('post_id', $post->id)->get();
      foreach ($comments as $comment) {
        $comment->delete();
      }
      $post->delete();
    }

    Storage::delete($group->avatar);
    $this->group->destroy($id);

    return redirect(route('group.index'))->with('ok', 'Le groupe a bien été supprimé');
  }

  public function searchResult(GroupSearchRequest $result)
  {
    if ($this->group->search($result->groupSearch)) {
      $group = $this->group->search($result->groupSearch);
      return redirect(route('group.show', ['name' => $group->name]));
    }

    return redirect(route('group.index'))->with('error', "Le groupe '" . $result->groupSearch . "' n'existe pas.");
  }

  public function joinDemand($groupName, $userId)
  {
    $user = User::where('id', $userId)->first();
    $group = Group::where('name', $groupName)->first();

    $groupOnDemand = explode(",", $group->on_demand);

    if (!in_array($user->id, $groupOnDemand)) {
      foreach ($groupOnDemand as $key => $value) {
        if (strlen($value) == 0) {
          array_splice($groupOnDemand, $key, 1);
        }
      }
      array_push($groupOnDemand, $user->id);
      $onDemand = implode(",", $groupOnDemand);
      $group->on_demand = $onDemand;
      $group->save();

      $groupAdmins = explode(",", $group->admins_id);
      foreach ($groupAdmins as $admin) {
        var_dump($admin);
        $userAdmin = User::where('id', $admin)->first();
        $userAdmin->notify(new JoinGroupDemand($user, $group));
      }

      return redirect(route('group.show', ['name' => $groupName]))->with('ok', "Votre demande pour rejoindre le groupe '" . $group->name . "' a été envoyée.");
    }

    return redirect(route('group.show', ['name' => $groupName]))->with('error', "Vous avez déjà envoyé une demande pour rejoindre le groupe '" . $groupName . "'");
  }
}
