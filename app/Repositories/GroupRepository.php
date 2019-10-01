<?php

namespace App\Repositories;

use App\Group;

class GroupRepository extends DataRepository
{
  public function __construct(Group $group)
  {
    $this->model = $group;
  }

  public function findPost($id)
  {
    return $this->model->find($id)->post;
  }

  public function findUser($id)
  {
    return $this->model->where('users_id', $id)->first();
  }

  public function findAdmin($id)
  {
    return $this->model->find($id)->admins_id;
  }

  public function search($request)
  {
    if ($this->model->where('name', 'like', '%' . $request . '%')->exists()) {
      if ($this->model->where('name',  'like', '%' . $request . '%')->count() == 1) {
        return $this->model->where('name',  'like', '%' . $request . '%')->first();
      }
      return $this->model->where('name',  'like', '%' . $request . '%')->get();
    }

    return false;
  }
}
