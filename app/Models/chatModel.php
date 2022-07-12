<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chatModel extends Model
{
    use HasFactory;
    protected $table='chats';
    protected $fillable=['user1','user2','chat_id'];
}
