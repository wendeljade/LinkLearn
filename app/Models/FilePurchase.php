<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_id',
        'status',
        'proof_of_payment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
