<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CommentRepository;
use App\Post;
use App\User;
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
        $post = Post::find(1)->where('id', $request->post_id)->first();
        $user = User::find(1)->where('id', $post->user_id)->first();

        $user->notify(new CommentNotification($user, $post));

        $inputs = array_merge($request->all());
        $this->comment->store($inputs);

        return redirect(route('blog.index'))->withOk('Le commentaire est enregistré.');
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
