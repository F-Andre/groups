<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'titre', 'contenu', 'user_id', 'image'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function deleteUserPosts($id)
    {
        $this->model->dropForeign($id);
    }
}
