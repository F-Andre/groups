<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'description', 'users_id', 'admins_id', 'on_demand', 'users_warned', 'avatar', 'active_at'
    ];

    protected $sortable = [
        'name'
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
