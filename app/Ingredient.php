<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{   protected $table ='ingredient';

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);

    }
    public function Alias()
    {
        return $this->hasMany(Alia::class,'ingredient_id');
    }

}
