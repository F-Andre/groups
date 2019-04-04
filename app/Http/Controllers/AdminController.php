<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $user;
    protected $nbrPerPage = 10;

    public function __construct(UserRepository $user)
    {
        $this->middleware('admin');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = 'name';
        $users = $this->user->getPaginate($this->nbrPerPage, $order);
        $links = $users->render();

        return view('admin.admin_home', compact('users', 'links'));
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
        $user = $this->user->getById($id);
        $posts = $this->user->nbrePosts($user->id);
        return view('admin.admin_user', compact('user', 'posts'));
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
        Storage::delete($user->avatar);
        foreach ($posts as $post) {
            Storage::delete($post->image);
            DB::table('posts')->where('user_id', $user->id)->delete();
        }
        $user->delete();

        return redirect()->route('admin.index')->withOk('Le compte a bien été effacé');
    }

    public function searchResult(SearchRequest $result)
    {
        if ($this->user->search($result->user) != false)
        {
            $user = $this->user->search($result->user);

            if ($user instanceof Collection)
            {
                $users = $user;
                $links = null;

                return view('admin.admin_home', compact('users', 'links'));
            }

            $posts = $this->user->nbrePosts($user->id);
            return view('admin.admin_user', compact('user', 'posts'));
        }

        return redirect(route('admin.index'))->withOk("Le terme recherché n'existe pas: " . $result->user);
    }

    public function orderChange($orderNew) {

        $users = $this->user->getPaginate($this->nbrPerPage, $orderNew);
        $links = $users->render();

        return view('admin.admin_home', compact('users', 'links'));
    }
}
