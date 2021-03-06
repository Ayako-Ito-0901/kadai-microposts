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

// コントローラーを経由してwelcomeページを表示上書きした
Route::get('/', 'MicropostsController@index');

// ユーザー登録 ->nameは名前を付けているだけ。後々に、Formやlink_to_route()で使用
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// ユーザー機能　ログイン認証を確認するような措置
Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    // 追加　多対多
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        // お気に入りの課題で追加したところ
        Route::get('favoriteposts', 'UsersController@favoriteposts')->name('users.favoriteposts');
        
    });
    
    // お気に入り課題で追加したところ
    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('favorite', 'UserFavoriteController@store')->name('favorites.favorite');
        Route::delete('unfavorite', 'UserFavoriteController@destroy')->name('favorites.unfavorite');
        
        
    }); 
        
    
    
    
    
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});

