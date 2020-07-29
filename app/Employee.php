<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        "first_name",
        "last_name",
    ];


    public function leave()
    {
        return $this->hasMany('App\Leave');
    }


}
