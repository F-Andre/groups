<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPost;
use Illuminate\Notifications\Notifiable;
use App\User;

class postController extends Controller
{
  protected $post;
  protected $nbrPerPage = 10;

  public function __construct(PostRepository $post)
  {
    $this->middleware('auth', ['except' => 'index']);

    $this->post = $post;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $posts = $this->post->getPaginate($this->nbrPerPage, 'nom');
    $links = $posts->render();


    return view('blog', compact('posts', 'links'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('post.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PostRequest $request)
  {
    $retour = redirect(route('blog.index'))->withOk('Le post "' . $request->titre . '" est enregistré.');
    $users = User::all();
    foreach ($users as $user) {
      if ($user->id != $request->user()->id) {
        $user->notify(new NewPost($request->user()));
      }
    }

    if ($request->hasFile('image')) {
      if ($request->image->isValid()) {
        if (Storage::exists('public/images/' . $request->user()->id) == false) {
          mkdir('storage/images/' . $request->user()->id, 0777, true);
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

        $inputs = array_merge($request->all(), ['user_id' => $request->user()->id, 'image' => $path]);
        $this->post->store($inputs);

        DB::table('users')->where('id', $request->user()->id)->increment('postsQty');

        return $retour;
      }
    }

    $inputs = array_merge($request->all(), ['user_id' => $request->user()->id]);
    $this->post->store($inputs);
    DB::table('users')->where('id', $request->user()->id)->increment('postsQty');

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
  public function edit($id)
  {
    $post = $this->post->getById($id);
    $imageUrl = Storage::url($post->image);
    return view('post.edit', ['id' => $post->id, 'titre' => $post->titre, 'contenu' => $post->contenu, 'imageUrl' => $imageUrl]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function update(PostRequest $request, $id)
  {
    $post = $this->post->getById($id);
    $retour = redirect(route('blog.index'))->withOk('Le post "' . $request->titre . '" a été modifié');

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
  public function destroy($id)
  {

    $user = $this->post->findUser($id);
    DB::table('users')->where('id', $user->id)->decrement('postsQty');

    $post = $this->post->getById($id);
    Storage::delete($post->image);

    $comments = $this->post->getComments($id);
    foreach ($comments as $comment) {
      DB::table('comments')->where('post_id', $post->id)->delete();
    }

    $this->post->destroy($id);

    return redirect()->back()->withOk('Le post a bien été effacé');
  }
}
