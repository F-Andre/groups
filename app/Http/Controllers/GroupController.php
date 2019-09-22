<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use App\Repositories\GroupRepository;
use App\Http\Requests\GroupSearchRequest;
use App\User;
use Illuminate\Support\Collection;

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
    $usersId = explode(" , ", $group->users_id);

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
    //
  }

  public function searchResult(GroupSearchRequest $result)
  {
    if ($this->group->search($result->groupSearch) !== null) {
      $group = $this->group->search($result->groupSearch);
      return redirect(route('group.show', ['name' => $group->name]));
    }

    return redirect(route('group.index'))->with('fail', "Le groupe recherché n'existe pas: " . $result->groupSearch);
  }

  public function join($groupName, $userId)
  {
    $user = User::where('id', $userId)->first();
    return redirect(route('group.show', ['name' => $groupName]))->with('fail', "demande envoyée " . $user->name);
  }
}
