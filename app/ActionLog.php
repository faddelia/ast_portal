<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
