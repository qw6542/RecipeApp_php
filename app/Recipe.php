<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected  $table = 'recipe';
    /************many to one************/
    public function user() {
        return $this -> belongsTo(User::class);
    }

    /**********many to many*********************/
    public function ingredient()
    {
        return $this->belongsToMany(Ingredient::class,'recipe_ingredient');

    }

    /*********one to many relationship*****************/
    public function description() {
        return $this -> hasMany(Description::class);
    }

    public function comment() {
        return $this -> hasMany(Ingredient::class);
    }
}
