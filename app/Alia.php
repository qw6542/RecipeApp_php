<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alia extends Model
{
    protected  $table ="alias";

    public function Ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
