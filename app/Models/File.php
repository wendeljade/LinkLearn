<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'title',
        'file_path',
        'price',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function purchases()
    {
        return $this->hasMany(FilePurchase::class);
    }

    public function isPurchasedBy($userId)
    {
        return $this->purchases()->where('user_id', $userId)->where('status', 'completed')->exists();
    }
}
