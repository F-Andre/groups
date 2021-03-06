<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CommentRepository;
use App\Post;
use App\User;
use App\Group;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{

  protected $comment;

  public function __construct(CommentRepository $comment)
  {
    $this->middleware('auth');
    $this->comment = $comment;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
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
    $post = Post::where('id', $request->post_id)->first();
    $poster = User::where('id', $post->user_id)->first();
    $commenter = User::where('id', $request->user_id)->first();
    $otherPostComments = $this->comment->getComments($post->id);
    $group = Group::where('id', $post->group_id)->first();
    $groupName = $group->name;

    $inputs = array_merge($request->all(), ['group_id' => $group->id]);

    $this->comment->store($inputs);

    if ($poster->id != $commenter->id && $poster->notifs == 'true') {
      $poster->notify(new CommentNotification($commenter, $post, $group));
    }

    foreach ($otherPostComments as $comment) {
      $user = User::where('id', $comment->user_id)->first();
      if ($user->id !== $poster->id && $user->notifs == 'true') {
        $user->notify(new CommentNotification($commenter, $post, $group));
      }
    }

    return redirect(route('posts.index', [$groupName, '#' . $post->id]));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
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
    $this->comment->destroy($id);

    return redirect()->back()->withOk('Le commentaire a bien été effacé');
  }
}
