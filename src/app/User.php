<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','profile_image', 'twitter_id'
    ];

    protected $visible = [
        'name', 'id', 'profile_image'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // リレーション
    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
    
    // 第1引数では最終的な接続先モデルを指定する
    // 第2引数では中間テーブル名を指定する
    // 第3引数では接続元モデルIDを示す中間テーブル内のカラムを指定する
    // 第4引数では接続先モデルIDを示す中間テーブル内のカラムを指定する
    public function followers()
    {
        return $this->belongsToMany(self::class, 'follow', 'followed_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(self::class, 'follow', 'following_id', 'followed_id');
    }

    // ユーザーの一覧を取得（自分以外）
    public function getAllUsers(Int $user_id)
    {
        return $this->where('id', '<>', $user_id)->paginate(5);
    }

    // ユーザーの一覧を取得
    public function usersAll()
    {
        return $this->paginate(5);
    }

    // ユーザーの一覧を取得(ページネーションなし)
    public function getUsers()
    {
        return $this->all();
    }

    // ユーザー情報の取得
    public function getUser($id)
    {
        return $this->where('id', $id)->first();
    }

    public function destroyUser(Int $user_id)
    {
        return $this->where('id', $user_id)->delete();
    }

    // プロフィール更新
    public function updateProfile(Array $params)
    {
        if(isset($params['profile_image'])) {
            // publicにファイルを保存
            $file_name = $params['profile_image']->store('public/profile_image');
            // S3にファイルを保存
            Storage::cloud()->putFileAs('', $params['profile_image'], basename($file_name), 'public');

            $this::where('id', $this->id)->update([
                'name' => $params['name'],
                'email' => $params['email'],
                'profile_image' => basename($file_name),
            ]);
        } else {
            $this::where('id', $this->id)->update([
                'name' => $params['name'],
                'email' => $params['email'],
            ]);
        }
        return;
    }

    // フォローする
    public function follow(Int $user_id)
    {
        return $this->follows()->attach($user_id);
    }

    // フォローを解除する
    public function unFollow(Int $user_id)
    {
        return $this->follows()->detach($user_id);
    }
    // フォローしているか
    public function isFollowing(Int $user_id)
    {
        return(boolean) $this->follows()->where('followed_id', $user_id)->first(['id']);
    }

    // フォローされているか
    public function isFollowed(Int $user_id)
    {
        return(boolean) $this->followers()->where('following_id', $user_id)->first(['id']);
    }

}
