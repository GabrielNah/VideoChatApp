<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class videoCallPartner extends Model
{
    protected $table='video_call_partners';
    protected $fillable=['user_id','video_call_room_id'];
    use HasFactory;

    public function partners()
    {
        $this->belongsTo(User::class,'id','user_id');
    }
    public function rooms(){
        $this->belongsTo(videoCallRooms::class,'id','video_call_room_id');
    }
}
