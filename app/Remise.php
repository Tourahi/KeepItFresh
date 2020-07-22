<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remise extends Model
{
    public function discount($total)
    {
    	return ($total * ($this->percent_off / 100));
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
