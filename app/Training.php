<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
   
  

	protected $fillable = [
        'training_name','training_deadline', 'status','file', 'youtube_link', 'video_file', 'slide', 'minimum_time', 'credit_hours'
    ];

    protected $casts = [
    	'training_deadline' => 'dd::mm:yyyy'
	];

	protected $dates = ['expired_at'];
}
