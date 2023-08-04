<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageUserRelation extends Model
{
    protected $table = 'message_user_relation';
    protected $fillable = [
        'message_id', 'sender_id','receiver_id','is_read'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id','id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id','id');
    }
}
