<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public  function recipe() {
        return $this -> hasMany(Recipe::class, 'user_id');
    }
    public  function favorite() {
        return $this -> hasMany(Recipe::class, 'user_id');
    }

    public  function question() {
        return $this -> hasMany(Question::class, 'user_id');
    }

    public  function comment() {
        return $this -> hasMany(Comment::class, 'user_id');
    }
}
