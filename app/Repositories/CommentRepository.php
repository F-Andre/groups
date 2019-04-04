<?php

namespace App\Repositories;

use App\Comment;

class CommentRepository extends DataRepository
{
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    public function getPaginate($n)
    {
        return $this->model->with('post')
        ->orderBy('comments.created_at', 'desc')
        ->paginate($n);
    }

    public function findUser($id)
    {
        return $this->model->find($id)->user;
    }

    public function findPost($id)
    {
        return $this->model->find($id)->post;
    }
}
