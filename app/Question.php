<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{   
	protected $fillable = [
        'training_id', 'question', 'option_one', 'option_two', 'option_three', 'option_four', 'correct_option'
    ];
}
