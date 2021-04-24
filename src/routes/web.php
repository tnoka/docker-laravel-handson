<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// TwitterAPI ソーシャルログイン機能
Route::get('auth/twitter', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/twitter/callback', 'Auth\SocialAuthController@handleProviderCallback');
Route::get('auth/twitter/logout', 'Auth\SocialAuthController@logout');
// TwitterAPI取得用
Route::get('terms',function(){return view('TermsOfService');});
Route::get('privacy',function(){return view('privacy');});

// 検索機能
Route::get('products/search', 'ProductController@search')->name('search');
// 非ログイン時のユーザー一覧
Route::get('users/all', 'UsersController@all')->name('all');

// ログイン状態のみ
Route::group(['middleware' => 'auth'], function(){
    
    // resourceでCRUDルーティングをまとめて設定
    // ユーザー関連
    Route::resource('users', 'UsersController');
    // フォロー一覧
    Route::get('followIndex/{id}', 'UsersController@followIndex')->name('followIndex');
    // フォロワー一覧
    Route::get('followerIndex/{id}', 'UsersController@followerIndex')->name('followerIndex');

    // 本関連
    Route::get('products/{product}', 'ProductController@show')->name('products.show');
    Route::resource('products', 'ProductController', ['only' => ['edit', 'update', 'destroy']]);
    Route::put('products/{products}', 'ProductController@update')->name('products.update');

    // コメント関連
    Route::resource('comments', 'CommentsController');

    // フォローする
    Route::post('users/{user}/follow', 'UsersController@follow')->name('follow');
    // フォロー解除
    Route::delete('users/{user}/unFollow', 'UsersController@unFollow')->name('unFollow');

    // いいね関連
    Route::get('favorites/{id}', 'FavoritesController@index')->name('favorites.index');
    Route::resource('favorites', 'FavoritesController');

});

Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');

// 認証系に必要なルーティング定義を一通り追加
Auth::routes();
