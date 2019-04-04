<?php

namespace App\Repositories;

use App\Post;

class PostRepository extends DataRepository
{
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function getPaginate($n)
    {
        return $this->model->with('user')
        ->orderBy('posts.created_at', 'desc')
        ->paginate($n);
    }

    public function findUser($id)
    {
        return $this->model->find($id)->user;
    }

    public function getComments($id)
    {
        return $this->model->find($id)->comments;
    }
}
