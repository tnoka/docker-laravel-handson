<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use App\Product;
use App\Follow;
use App\Favorite;

class UsersController extends Controller
{
    // ユーザー一覧
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users' => $all_users
        ]);
    }

    public function all(User $user)
    {
        $all_users = $user->usersAll();

        return view('users.all', [
            'all_users' => $all_users
        ]);
    }

    // ユーザー詳細
    public function show(User $user, Product $product, Follow $follow, Favorite $favorite)
    {
        $login_user = auth()->user();
        $is_following = $login_user->isFollowing($user->id);
        $is_followed = $login_user->isFollowed($user->id);
        $timelines = $product->getUserTimeLine($user->id);
        $product_count = $product->getProductCount($user->id);
        $follow_count = $follow->getFollowCount($user->id);
        $follower_count = $follow->getFollowerCount($user->id);
        $favorite_count = $favorite->getFavoriteCount($user->id);

        return view('users.show', [
            'user' => $user,
            'is_following' => $is_following,
            'is_followed' => $is_followed,
            'timelines' => $timelines,
            'product_count' => $product_count,
            'follow_count' => $follow_count,
            'follower_count' => $follower_count,
            'favorite_count' => $favorite_count,
        ]);
    }
    // フォロー一覧
    public function followIndex(User $user, Product $product, Follow $follow, Favorite $favorite, $id)
    {
        $login_user = auth()->user();
        $user = $user->getUser($id);
        $is_following = $login_user->isFollowing($id);
        $is_followed = $login_user->isFollowed($id);
        $product_count = $product->getProductCount($id);
        $follows = $user->getUsers($id);
        $follow_count = $follow->getFollowCount($id);
        $follower_count = $follow->getFollowerCount($id);
        $favorite_count = $favorite->getFavoriteCount($id);

        return view('users.followIndex', [
            'user' => $user,
            'is_following' => $is_following,
            'is_followed' => $is_followed,
            'product_count' => $product_count,
            'follows' => $follows,
            'follow_count' => $follow_count,
            'follower_count' => $follower_count,
            'favorite_count' => $favorite_count,
        ]);
    }
    // フォロワー一覧
    public function followerIndex(User $user, Product $product, Follow $follow, Favorite $favorite, $id)
    {
        $login_user = auth()->user();
        $user = $user->getUser($id);
        $is_following = $login_user->isFollowing($id);
        $is_followed = $login_user->isFollowed($id);
        $product_count = $product->getProductCount($id);
        $followers = $user->getUsers($id);
        $follow_count = $follow->getFollowCount($id);
        $follower_count = $follow->getFollowerCount($id);
        $favorite_count = $favorite->getFavoriteCount($id);

        return view('users.followerIndex', [
            'user' => $user,
            'is_following' => $is_following,
            'is_followed' => $is_followed,
            'product_count' => $product_count,
            'followers' => $followers,
            'follow_count' => $follow_count,
            'follower_count' => $follower_count,
            'favorite_count' => $favorite_count,
        ]);
    }

    // ユーザー編集
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    // プロフィール更新
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', Rule::unique('users')->ignore($user->id),
            'profile_image' => 'file|image|mimes:jpeg,jpg,png|max:8192'
        ]);
        $validator->validate();
        $user->updateProfile($data);

        return redirect('users/'.$user->id);
    }

    // アカウント削除
    public function destroy(User $user)
    {
        $user = auth()->user();
        $user->destroyUser($user->id);

        return redirect('/');    
    }

    // フォロー
    public function follow(User $user)
    {
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        if(!$is_following) {
            // フォローしていなければフォローする
            $follower->follow($user->id);
            return back();
        }
    }

    // フォロー解除
    public function unFollow(User $user)
    {
        $follower = auth()->user();
        // フォローしているか
        $is_following = $follower->isFollowing($user->id);
        if($is_following) {
            // フォローしていればフォローを解除する
            $follower->unFollow($user->id);
            return back();
        }
    }
}