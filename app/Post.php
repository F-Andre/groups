<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use Notifiable;

    protected $fillable = [
        'titre', 'contenu', 'image', 'user_id', 'group_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
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
