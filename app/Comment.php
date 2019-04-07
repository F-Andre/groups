<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    use Sortable, Notifiable;

    protected $fillable = [
        'comment', 'post_id', 'user_id'
    ];

    public $sortable = [
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function deleteUserComments($id)
    {
        $this->model->dropForeign($id);
    }
}
