<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected  $table ="comment";
    public function recipe(){
        return $this->belongsTo(Recipe::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
