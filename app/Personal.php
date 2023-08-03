<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    public $fillable = ['user_id','title','url'];
}
