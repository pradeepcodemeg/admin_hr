<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submit_training extends Model
{
    protected $fillable = [
        'firstname', 'lastname','training_name', 'passed','passing_date', 'credit_hours'
    ];
}
