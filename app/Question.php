<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    public function asked(){
        return $this->belongsTo(User::class);
    }
    public function to(){
        return $this->belongsTo(User::class);
    }
}
