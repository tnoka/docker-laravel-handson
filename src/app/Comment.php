<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{   
    // 変更を許可する
    protected $fillable = [
        'text'
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function commentStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->product_id = $data['product_id'];
        $this->text = $data['text'];
        $this->save();
    }

    public function getComment($product_id)
    {
        return $this->with('author')->where('product_id', $product_id)->get();
    }

    public function commentDelete(Int $user_id, Int $comment_id)
    {
        return $this->where('user_id', $user_id)->where('id', $comment_id)->delete();
    }
}
