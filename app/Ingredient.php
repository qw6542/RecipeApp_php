<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{   protected $table ='ingredient';

    public function recipe()
    {
        return $this->belongsToMany(Recipe::class,'recipe_ingredient');

    }
}
