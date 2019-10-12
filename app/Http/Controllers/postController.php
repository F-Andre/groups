<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\PostRequest;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Notifications\NewPost;
use App\User;
use App\Group;
use Carbon\Carbon;
use App\Post;

class postController extends Controller
{
  protected $post;
  protected $nbrPerPage = 10;

  public function __construct(PostRepository $post)
  {
    $this->middleware('auth');

    $this->post = $post;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($groupName)
  {
    if (isset($groupName))
    {
      $posts = $this->post->getCollectionOrdered();

      $group = Group::where('name', $groupName)->first();
      $groupUsers = explode(",", $group->users_id);
      $groupAdmins = explode(",", $group->admins_id);
      $groupOnDemand = explode(",", $group->on_demand);

      $nbrPosts = $group->posts()->count();

      if (in_array(auth()->user()->id, $groupUsers))
      {
        return view('blog', compact('posts', 'groupName', 'group', 'nbrPosts', 'groupAdmins', 'groupOnDemand'));
      }
      else
      {
        return redirect(route('group.index'));
      }
    } 
    else
    {
      return redirect(route('group.index'));
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($groupName)
  {
    $group = Group::where('name', $groupName)->first();
    $groupAdmins = explode(",", $group->admins_id);

    return view('post.create', compact('groupName', 'groupAdmins'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PostRequest $request, $groupName)
  {
    $retour = redirect(route('posts.index', $groupName))->withOk('Le post "' . $request->titre . '" est enregistré.');
    $group = Group::where('name', $groupName)->first();
    $groupUsers = explode(",", $group->users_id);
    $users = User::all();

    if ($request->hasFile('image')) {
      if ($request->image->isValid()) {
        if (Storage::exists('public/images/' . $request->user()->id) == false) {
          Storage::makeDirectory('public/images/' . $request->user()->id);
        }

        $fileExt = $request->image->getClientOriginalExtension();
        $fileName = Str::random(15);

        while (Storage::exists('public/images/' . $request->user()->id . '/' . $fileName . '.' . $fileExt)) {
          $fileName = Str::random(15);
        }

        $path = 'public/images/' . $request->user()->id . '/' . $fileName . '.' . $fileExt;

        $pathUrl = Storage::url($path);
        $imageMake = Image::make($request->image);
        $imageMake->widen(900, function ($constraint) {
          $constraint->upsize();
        });
        $imageMake->save('.' . $pathUrl);

        $inputs = array_merge($request->all(), ['user_id' => $request->user()->id, 'image' => $path, 'group_id' => $group->id]);
        $this->post->store($inputs);

        $post = Post::where('titre', $request->titre)->latest()->first();

        foreach ($users as $user) {
          if ($user->id != $request->user()->id && in_array($user->id, $groupUsers)) {
            $user->notify(new NewPost($request->user(), $post, $group));
          }
        }

        $group->update(['active_at' => Carbon::now()]);

        return $retour;
      }
    }

    $inputs = array_merge($request->all(), ['user_id' => $request->user()->id, 'group_id' => $group->id]);
    $this->post->store($inputs);

    $group->update(['active_at' => Carbon::now()]);

    $post = Post::where('titre', $request->titre)->latest()->first();

    foreach ($users as $user) {
      if ($user->id != $request->user()->id && in_array($user->id, $groupUsers)) {
        $user->notify(new NewPost($request->user(), $post, $group));
      }
    }

    return $retour;
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function show(Post $post)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function edit($groupName, $id)
  {
    $post = $this->post->getById($id);
    $imageUrl = Storage::url($post->image);
    $group = Group::where('name', $groupName)->first();
    $groupAdmins = explode(",", $group->admins_id);

    return view('post.edit', ['groupName' => $groupName, 'groupAdmins' => $groupAdmins, 'id' => $post->id, 'titre' => $post->titre, 'contenu' => $post->contenu, 'imageUrl' => $imageUrl]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function update(PostRequest $request, $groupName, $id)
  {
    $post = $this->post->getById($id);
    $retour = redirect(route('posts.index', $groupName))->withOk('Le post "' . $request->titre . '" a été modifié');

    if ($request->hasFile('image')) {
      if ($request->image->isValid()) {
        $oldImage = $post->image;

        $fileExt = $request->image->getClientOriginalExtension();
        $fileName = Str::random(15);
        while (Storage::exists('public/images/' . $request->user()->id . '/' . $fileName . '.' . $fileExt)) {
          $fileName = Str::random(15);
        }

        $path = 'public/images/' . $request->user()->id . '/' . $fileName . '.' . $fileExt;

        $pathUrl = Storage::url($path);
        $imageMake = Image::make($request->image);
        $imageMake->widen(900, function ($constraint) {
          $constraint->upsize();
        })->save('.' . $pathUrl);

        $inputs = array_merge($request->all(), ['image' => $path]);
        $post->update($inputs);

        Storage::delete($oldImage);

        return $retour;
      }
    }

    if (strlen($post->image) > 1 && $request->imageDeleted) {
      $oldImage = $post->image;
      Storage::delete($oldImage);

      $inputs = array_merge($request->all(), ['image' => 0]);
      $post->update($inputs);

      return $retour;
    }

    $post->update($request->all());

    return $retour;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function destroy($groupName, $id)
  {
    $post = $this->post->getById($id);

    $user = User::where('id', $post->user_id)->first();
    $user->decrement('postsQty');

    Storage::delete($post->image);

    $comments = Comment::where('post_id', $post->id)->get();

    foreach ($comments as $comment) {
      $comment->delete();
    }

    $this->post->destroy($id);

    return redirect(route('posts.index', $groupName))->with('ok', 'Le post a bien été effacé');
  }
}
