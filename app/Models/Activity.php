<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'title',
        'description',
        'deadline',
        'file_path',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
