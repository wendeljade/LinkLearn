<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'room_id',
        'content',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
