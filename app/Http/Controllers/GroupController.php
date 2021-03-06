<?php

namespace App\Http\Controllers;

use App\Group;
use App\Post;
use App\User;
use App\Comment;
use App\Repositories\GroupRepository;
use App\Http\Requests\GroupSearchRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;

use App\Notifications\JoinGroupDemand;
use App\Notifications\ContactAdmin;
use App\Mail\InvitNewMember;
use App\Repositories\UserRepository;

class GroupController extends Controller
{

  protected $group;
  protected $user;

  public function __construct(GroupRepository $group, UserRepository $user)
  {
    $this->middleware('auth');
    $this->group = $group;
    $this->user = $user;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = auth()->user();
    $userGroups = $this->group->getIncludesIdsByName(explode(',', $user->groups_id));
    $notUserGroups = $this->group->getNotIncludesIdsByName(explode(',', $user->groups_id));

    return view('group.group_index', compact('userGroups', 'notUserGroups'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $avatarUrl = Storage::url('public/default/default-group.svg');
    return view('group.group_create', compact('avatarUrl'));
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

    $group = Group::where('name', $request->name)->first();

    if ($request->hasFile('avatar')) {
      if ($request->avatar->isValid()) {
        Storage::makeDirectory('public/group-avatar/' . $group->id);

        $fileExt = $request->avatar->getClientOriginalExtension();
        $fileName = Str::random(15);

        while (Storage::exists('public/group-avatar/' . $group->id . '/' . $fileName . '.' . $fileExt)) {
          $fileName = Str::random(15);
        }

        $path = 'public/group-avatar/' . $group->id . '/' . $fileName . '.' . $fileExt;

        $pathUrl = Storage::url($path);
        $imageMake = Image::make($request->avatar);
        $imageMake->widen(256, function ($constraint) {
          $constraint->upsize();
        });
        $imageMake->save('.' . $pathUrl);

        $group->update(['avatar' => $path]);
      }
    }

    $user = $this->user->getById($request->id);
    $userGroupsArray = explode(',', $user->groups_id);
    array_push($userGroupsArray, $group->id);
    $userGroup = implode(',', $userGroupsArray);

    $user->update(['groups_id' => $userGroup]);

    return redirect(route('group.index'));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($groupName)
  {
    $group = $this->group->getByName($groupName);
    $usersId = explode(",", $group->users_id);
    $adminsId = explode(",", $group->admins_id);
    $users = User::whereIn('id', $usersId)->get();
    $dateCreation = Carbon::parse($group->created_at)->locale('fr')->timezone('Europe/Paris')->format('d M Y à H:i');

    return view('group.group_show', compact('group', 'usersId', 'dateCreation', 'users', 'adminsId'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $group = $this->group->getById($id);
    $adminsId = explode(",", $group->admins_id);
    $avatarUrl = Storage::url($group->avatar);
    $defaultAvatar = Storage::url('public/default/default-group.svg');

    return view('group.group_edit', array_merge(compact('group', 'adminsId'), ['avatarUrl' => $avatarUrl], ['defaultAvatar' => $defaultAvatar]));
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
    $group = $this->group->getById($id);
    $retour = redirect(route('admin.index', $group->name))->withOk('Les informations du groupe ont été mises à jour.');

    if ($request->hasFile('avatar')) {
      if ($request->avatar->isValid()) {
        $oldImage = $group->avatar;

        if (Storage::exists('public/group-avatar/' . $group->id) == false) {
          Storage::makeDirectory('public/group-avatar/' . $group->id);
        } else {
          $files = Storage::files('public/group-avatar/' . $group->id);
          Storage::delete($files);
        }

        $fileExt = $request->avatar->getClientOriginalExtension();
        $fileName = Str::random(15);

        while (Storage::exists('public/group-avatar/' . $group->id . '/' . $fileName . '.' . $fileExt)) {
          $fileName = Str::random(15);
        }

        $path = 'public/group-avatar/' . $group->id . '/' . $fileName . '.' . $fileExt;

        $pathUrl = Storage::url($path);
        $imageMake = Image::make($request->avatar);
        $imageMake->widen(256, function ($constraint) {
          $constraint->upsize();
        });
        $imageMake->save('.' . $pathUrl);

        $inputs = array_merge($request->all(), ['avatar' => $path]);
        $group->update($inputs);

        if ($oldImage != 'public/default/default-group.svg') {
          Storage::delete($oldImage);
        }

        return $retour;
      }
    }

    if (strlen($group->avatar) > 1 && $request->avatarDeleted) {
      $oldImage = $group->avatar;

      if ($oldImage != 'public/default/default-group.svg') {
        Storage::delete($oldImage);
      }

      $inputs = array_merge($request->all(), ['avatar' => 'public/default/default-group.svg']);
      $group->update($inputs);

      return $retour;
    }

    $group->update($request->all());

    return $retour;
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
      return redirect(route('group.show', $group->name));
    }

    return redirect(route('group.index'))->with('error', "Le groupe '" . $result->groupSearch . "' n'existe pas.");
  }

  public function joinDemand($groupName, $userId)
  {
    $user = User::where('id', $userId)->first();
    $group = $this->group->getByName($groupName);

    $groupOnDemand = explode(",", $group->on_demand);

    if (!in_array($user->id, $groupOnDemand)) {
      foreach ($groupOnDemand as $key => $value) {
        if (strlen($value) == 0) {
          array_splice($groupOnDemand, $key, 1);
        }
      }
      array_push($groupOnDemand, $user->id);
      $onDemand = implode(",", $groupOnDemand);

      $group->update(['on_demand' => $onDemand]);

      $groupAdmins = explode(",", $group->admins_id);
      foreach ($groupAdmins as $admin) {
        $userAdmin = User::where('id', $admin)->first();
        $userAdmin->notify(new JoinGroupDemand($group, $user));
      }

      return redirect(route('group.show', $groupName))->with('ok', "Votre demande pour rejoindre le groupe '" . $group->name . "' a été envoyée.");
    }

    return redirect(route('group.show', $groupName))->with('error', "Vous avez déjà envoyé une demande pour rejoindre le groupe '" . $groupName . "'");
  }

  public function invitMember(Request $request, $groupName, $userId)
  {
    $group = $this->group->getByName($groupName);
    $user = User::where('id', $userId)->first();
    $mails = explode(",", $request->emails);

    foreach ($mails as $mail) {
      if (strlen($mail) > 0) {
        $mailTrim = trim($mail);
        Mail::send(new InvitNewMember($group, $user, $mailTrim));
      }
    }

    return redirect(route('group.show', $groupName))->with('ok', "Les invitations ont été envoyées.");
  }

  public function contactModal(Request $request, $groupName, $userId)
  {
    $group = $this->group->getByName($groupName);
    $user = User::where('id', $userId)->first();

    $subject = $request->subject;
    $message = $request->message;

    if (is_array($request->receiverId)) {
      foreach ($request->receiverId as $receiverId) {
        $receiverUser = User::find($receiverId);
        $receiverUser->notify(new ContactAdmin($group, $user, $subject, $message));
      }
    } else {
      $receiverUser = User::find($request->receiverId);
      $receiverUser->notify(new ContactAdmin($group, $user, $subject, $message));
    }

    return redirect()->back()->with('ok', "Le message a été envoyé.");
  }
}
