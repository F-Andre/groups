<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Post;

class UserController extends Controller
{

  protected $user;
  protected $nbrPerPage = 10;

  public function __construct(UserRepository $user)
  {
    $this->middleware('auth');
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
    return view('user.user_home', compact('user'));
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
  public function show($id)
  {
    $orderValue = 'created_at';
    $ord = 'desc';

    if (isset($_GET['tri'])) {
      if ($_GET['tri'] == 'titre') {
        $orderValue = 'titre';
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

    if (isset($_GET['post-view'])) {
        $post = Post::find(1)->where('id', $_GET['post-view'])->first();
    }

    $user = $this->user->getById($id);
    $posts = DB::table('posts')->orderBy($orderValue, $ord)->where('user_id', $user->id)->get();

    return isset($post) ?  view('user.user_posts', compact('user', 'posts', 'post')) : view('user.user_posts', compact('user', 'posts'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = $this->user->getById($id);
    $avatarUrl = Storage::url($user->avatar);
    $defaultAvatar = Storage::url('public/default/default_avatar.png');
    return view('user.user_edit', array_merge(compact('user'), ['avatarUrl' => $avatarUrl], ['defaultAvatar' => $defaultAvatar]));
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
    $user = $this->user->getById($id);
    $retour = redirect(route('user_page.index'))->withOk('Vos informations ont été mises à jour.');

    if ($request->hasFile('avatar')) {
      if ($request->avatar->isValid()) {
        $oldImage = $user->avatar;

        if (Storage::exists('public/avatar/' . $user->id) == false) {
          mkdir('storage/avatar/' . $user->id, 0775, true);
        }

        $fileExt = $request->avatar->getClientOriginalExtension();
        $fileName = Str::random(15);

        while (Storage::exists('public/avatar/' . $user->id . '/' . $fileName . '.' . $fileExt)) {
          $fileName = Str::random(15);
        }

        $path = 'public/avatar/' . $user->id . '/' . $fileName . '.' . $fileExt;

        $pathUrl = Storage::url($path);
        $imageMake = Image::make($request->avatar);
        $imageMake->widen(256, function ($constraint) {
          $constraint->upsize();
        });
        $imageMake->save('.' . $pathUrl);

        $inputs = array_merge($request->all(), ['avatar' => $path]);
        $user->update($inputs);

        if ($oldImage != 'public/default/default_avatar.png') {
          Storage::delete($oldImage);
        }

        return $retour;
      }
    }

    if (strlen($user->avatar) > 1 && $request->avatarDeleted) {
      $oldImage = $user->avatar;

      if ($oldImage != 'public/default/default_avatar.png') {
        Storage::delete($oldImage);
      }

      $inputs = array_merge($request->all(), ['avatar' => 'public/default/default_avatar.png']);
      $user->update($inputs);

      return $retour;
    }

    $user->update($request->all());

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
    $user = $this->user->getById($id);
    $posts = $this->user->nbrePosts($user->id);
    $comments = $this->user->nbreComments($user->id);

    $avatar = $user->avatar;

    if ($avatar != 'public/default/default_avatar.png') {
      Storage::delete($avatar);
    }

    foreach ($posts as $post) {
      Storage::delete($post->image);
      DB::table('posts')->where('user_id', $user->id)->delete();
    }

    foreach ($comments as $comment) {
      DB::table('comments')->where('user_id', $user->id)->delete();
    }

    $user->delete();

    return redirect()->route('blog.index')->withOk('Le compte a bien été effacé');
  }
}
