<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
