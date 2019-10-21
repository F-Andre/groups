<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\Group;
use App\Notifications\DeregisterUser;
use App\Notifications\GroupDeregister;
use App\Notifications\JoinGroupFalse;
use App\Notifications\JoinGroupOk;
use App\Notifications\JoinRejectAdminNotif;
use App\Notifications\NewMember;
use App\Notifications\WarnAdminNotif;
use App\Notifications\WarnUserNotif;
use Carbon\Carbon;

class AdminController extends Controller
{
  protected $user;
  protected $nbrPerPage = 10;

  public function __construct(UserRepository $user)
  {
    $this->user = $user;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($groupName)
  {
    $order = 'name';

    $group = Group::where('name', $groupName)->first();
    $groupAdmins = explode(",", $group->admins_id);
    $groupUsers = explode(",", $group->users_id);
    $groupOnDemand = explode(",", $group->on_demand);
    $usersWarned = explode(",", $group->users_warned);
    $dateCreation = Carbon::parse($group->created_at)->locale('fr')->timezone('Europe/Paris')->format('d M Y à H:i');

    $posts = Post::where('group_id', $group->id)->get();
    $comments = Comment::where('group_id', $group->id)->get();

    $users = $this->user->getPaginate($this->nbrPerPage, $order);
    $links = $users->render();

    return view('admin.admin_home', compact('group', 'dateCreation', 'groupAdmins', 'groupOnDemand', 'usersWarned', 'users', 'groupUsers', 'posts', 'comments', 'links'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($groupName, $id)
  {
    $orderValue = 'created_at';
    $ord = 'desc';

    if (isset($_GET['tri'])) {
      if ($_GET['tri'] == 'titre') {
        $orderValue = 'titre';
        $ord = 'asc';
      } elseif ($_GET['tri'] == 'created-asc') {
        $orderValue = 'created_at';
        $ord = 'asc';
      } elseif ($_GET['tri'] == 'created-desc') {
        $orderValue = 'created_at';
        $ord = 'desc';
      } elseif ($_GET['tri'] == 'updated-asc') {
        $orderValue = 'updated_at';
        $ord = 'asc';
      } elseif ($_GET['tri'] == 'created-desc') {
        $orderValue = 'updated_at';
        $ord = 'desc';
      }
    }

    $group = Group::where('name', $groupName)->first();
    $groupAdmins = explode(",", $group->admins_id);
    $groupUsers = explode(",", $group->users_id);
    $user = $this->user->getById($id);
    $posts = Post::where('user_id', $user->id)->orderBy($orderValue, $ord)->get();
    return view('admin.admin_user', compact('groupName', 'group', 'groupAdmins', 'groupUsers', 'user', 'posts'));
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
    $user = $this->user->getById($id);
    $posts = $this->user->nbrePosts($user->id);
    $avatar = $user->avatar;

    if ($avatar != 'public/default/default_avatar.png') {
      Storage::delete($avatar);
    }

    foreach ($posts as $post) {
      Storage::delete($post->image);
      Post::where('user_id', $user->id)->delete();
    }
    $user->delete();

    return redirect()->route('admin.index')->withOk('Le compte a bien été effacé');
  }

  public function searchResult(SearchRequest $result, $groupName)
  {
    $group = Group::where('name', $groupName)->first();
    $groupAdmins = explode(",", $group->admins_id);

    if ($this->user->search($result->user) != false) {
      $user = $this->user->search($result->user);

      if ($user instanceof Collection) {
        $users = $user;        

        return view('admin.admin_home', compact('users', 'group', 'groupName', 'groupAdmins'));
      }

      $posts = $this->user->nbrePosts($user->id);
      return view('admin.admin_user', compact('user', 'posts', 'group', 'groupName', 'groupAdmins'));
    }

    return redirect(route('admin.index', compact('groupName')))->with("error", "L'utilisateur recherché n'existe pas: " . $result->user);
  }

  public function deregisterUser($groupName, Request $request)
  {
    $group = Group::where('name', $groupName)->first();
    $user = $this->user->getById($request->user_id);
    $userPosts = Post::where('user_id', $user->id)->get();
    $userComments = Comment::where('user_id', $user->id)->get();
    $admins = explode(",", $group->admins_id);

    foreach ($userPosts as $post) {
      if ($post->group_id == $group->id) {
        foreach ($userComments as $comment) {
          if ($comment->post_id == $post->id) {
            $comment->delete();
          }
        }
        $post->delete();
      }
    }

    $groupUsers = explode(",", $group->users_id);
    $userGroupKey = array_search($user->id, $groupUsers);
    array_splice($groupUsers, $userGroupKey, 1);
    $newGroupUsers = implode(",", $groupUsers);
    $group->users_id = $newGroupUsers;

    $userWarned = explode(",", $group->users_warned);
    while (array_search($user->id, $userWarned)) {
      $userWarnedKey = array_search($user->id, $userWarned);
      array_splice($userWarned, $userWarnedKey, 1);
    }
    $newUserWarned = implode(",", $userWarned);
    $group->users_warned = $newUserWarned;

    $group->save();

    foreach($admins as $admin)
    {
      $adminUser = $this->user->getById($admin);
      $adminUser->notify(new DeregisterUser($group, $user, $request->reason));
    }

    $user->notify(new GroupDeregister($user, $group, $request->reason));

    return redirect(route('admin.index', $groupName))->with('ok', $user->name . " a bien été radié du groupe.");
  }

  public function joinGroup(Request $request, $groupName)
  {
    $group = Group::where('name', $groupName)->first();
    $user = $this->user->getById($request->user_id);

    $groupOnDemand = explode(",", $group->on_demand);

    if ($request->join == 'true') {
      $groupUsers = explode(",", $group->users_id);
      array_push($groupUsers, $user->id);
      $newGroupUsers = implode(",", $groupUsers);

      $userDemandKey = array_search($user, $groupOnDemand);
      array_splice($groupOnDemand, ($userDemandKey - 1), 1);
      $newOnDemand = implode(",", $groupOnDemand);

      $group->on_demand = $newOnDemand;
      $group->users_id = $newGroupUsers;
      $group->save();

      foreach($groupUsers as $groupUserId)
      {
        $groupUser = $this->user->getById($groupUserId);
        if ($groupUser->notifs)
        {
          $groupUser->notify(new NewMember($user, $group));
        }
      }

      $user->notify(new JoinGroupOk($user, $group));

      return redirect(route('admin.index', $groupName))->with('ok', "L'utilisateur " . $user->name . " a bien été ajouté au groupe.");

    } else {
      $userDemandKey = array_search($user, $groupOnDemand);
      array_splice($groupOnDemand, ($userDemandKey - 1), 1);
      $newOnDemand = implode(",", $groupOnDemand);

      $group->on_demand = $newOnDemand;
      $group->save();

      $adminsId = explode(",", $group->admins_id);
      foreach($adminsId as $adminId)
      {
        $admin = $this->user->getById($adminId);
        $admin->notify(new JoinRejectAdminNotif($user, $group));
      }

      $user->notify(new JoinGroupFalse($user, $group));

      return redirect(route('admin.index', $groupName))->with('ok', "La demande de l'utilisateur " . $user->name . " a bien été rejetée.");
    }
  }

  public function warnUser(Request $request, $groupName)
  {
    $group = Group::where('name', $groupName)->first();
    $user = $this->user->getById($request->user_id);
    $admins = explode(",", $group->admins_id);

    $warnedUsers = explode(",", $group->users_warned);
    array_push($warnedUsers, $user->id);
    $newWarnedUsers = implode(",", $warnedUsers);

    $group->users_warned = $newWarnedUsers;
    $group->save();

    foreach($admins as $admin)
    {
      $adminUser = $this->user->getById($admin);
      $adminUser->notify(new WarnAdminNotif($user, $group, $request->reason));
    }

    $user->notify(new WarnUserNotif($user, $group, $request->reason));

    return redirect(route('admin.index', $groupName))->with('ok', "L'utilisateur " . $user->name . " a bien été averti.");
  }

  public function adminSwitch(Request $request, $groupName)
  {
    $group = Group::where('name', $groupName)->first();
    $user = $this->user->getById($request->user_id);
    $adminsId = explode(",", $group->admins_id);

    if ($request->admin) {
      array_push($adminsId, $user->id);
      $newAdminsId = implode(",", $adminsId);

      $group->admins_id = $newAdminsId;
      $group->save();

      return redirect(route('admin.index', $groupName))->with('ok', "L'utilisateur " . $user->name . " a été ajouté aux administrateurs.");
    }

    $adminsIdKey = array_search($user, $adminsId);
    array_splice($adminsId, $adminsIdKey, 1);
    $newAdminsId = implode(",", $adminsId);

    $group->admins_id = $newAdminsId;
    $group->save();

    return redirect(route('admin.index', $groupName))->with('ok', "L'utilisateur " . $user->name . " a été retiré des administrateurs.");
  }
}
