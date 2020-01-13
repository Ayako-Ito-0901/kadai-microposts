<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts() {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings() {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function followers() {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId) {
        // 既にフォローしているか確認する
        $exist = $this->is_following($userId);
        // 相手が自分自身でないか確認
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            // 既にフォローしている場合は何もしない
            return false;
        }
        else {
            // 未フォローの場合はフォロー
            $this->followings()->attach($userId);
            return true;
        }
        
    }
    
    public function unfollow($userId) {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            // フォローを外す
            $this->followings()->detach($userId);
            return true;
        }
        else {
            // 未フォローの場合は何もしない
            return false;
        }
    }
    
    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    // お気に入り課題で追加 カリキュラムのfollowingsにあたる
    public function favoriteposts() {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    // お気に入り登録するfunctionを定義
    public function favorite($micropostId) {
        // お気に入り登録しているか確認し結果をexistに
        $exist = $this->is_favorite($micropostId);
        // 登録していれば何もしない
        if ($exist) {
            return false;
        }
        else {
            $this->favoriteposts()->attach($micropostId);
            return true;
        }
    }
    
    // お気に入りをやめるfunctionを定義
    public function unfavorite($micropostId) {
        // お気に入り登録しているかを確認し、結果をexistに
        $exist = $this->is_favorite($micropostId);
        
        if ($exist) {
            $this->favoriteposts()->detach($micropostId);
            return true;
        }
        else {
            return false;
        }
    }
    
    
    
    // お気に入り課題で追加　お気に入り登録を確認するメソッド
    public function is_favorite($micropostId) {
        return $this->favoriteposts()->where('micropost_id', $micropostId)->exists();
    }
    
    
    
    
    
}
