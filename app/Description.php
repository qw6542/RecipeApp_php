<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class description extends Model
{

    protected  $table ="description";
    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
}
