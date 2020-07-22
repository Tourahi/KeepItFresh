<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = ['stock'];

    public function getPrice()
    {
    	return number_format($this->price / 100 , 2, ',' , '')." MAD";
    }
    
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function fournisseur()
    {
        return $this->belongsTo('App\Fournisseur');
    }

}
