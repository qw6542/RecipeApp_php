<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected  $table = 'recipe';

    public function user() {
        return $this -> belongsTo(User::class);
    }


    public function ingredient()
    {
        return $this->hasMany(Ingredient::class,'recipe_id');

    }

    public  function follower() {
        return $this -> hasMany(User::class, 'recipe_id');
    }


    public function description() {
        return $this -> hasMany(Description::class,'recipe_id');
    }

    public function comment() {
        return $this -> hasMany(Ingredient::class,'recipe_id');
    }
}
