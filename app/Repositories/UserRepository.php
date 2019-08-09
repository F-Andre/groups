<?php

namespace App\Repositories;

use App\User;

class UserRepository extends DataRepository
{
  public function __construct(User $user)
  {
    $this->model = $user;
  }

  public function getPaginate($n, $order)
  {
    return $this->model->sortable()->orderBy($order)
      ->paginate($n);
  }

  public function nbrePosts($id)
  {
    return $this->model->find($id)->userPosts;
  }

  public function nbreComments($id)
  {
    return $this->model->find($id)->userComments;
  }

  public function search($request)
  {
    if ($this->model->where('name', 'like', '%' . $request . '%')->exists()) {
        if ($this->model->where('name',  'like', '%' . $request . '%')->count() == 1) {
            return $this->model->where('name',  'like', '%' . $request . '%')->first();
          }
        return $this->model->where('name',  'like', '%' . $request . '%')->get();
      } elseif ($this->model->where('email', $request)->exists()) {
        return $this->model->where('email', $request)->first();
      }

    return false;
  }

  public function entryExist($entry, $request)
  {
    
    if ($this->model->where($entry, $request)->exists()) {
      return true;
    }
    
    return false;
  }
}
