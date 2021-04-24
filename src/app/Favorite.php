<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Favorite extends Model
{
    protected $table = 'favorite';
    public $timestamps = false;

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFavorite($id)
    {
        return $this->where('user_id', $id)->paginate(6);
    }

    // 読みたい本の判定
    public function checkFavorite(Int $user_id, $product_id)
    {
        return (boolean) $this->where('user_id', $user_id)->where('product_id', $product_id)->first();
    }

    // 読みたい本に追加
    public function storeFavorite(Int $user_id, $product_id)
    {
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->save();

        return;
    }

    // 読みたい本を解除
    public function destroyFavorite(Int $favorite_id)
    {
        return $this->where('id', $favorite_id)->delete();
    }

    public function getFavoriteCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    public function getFavoritesCount($id)
    {
        return $this->where('user_id', $id)->count();
    }
}
