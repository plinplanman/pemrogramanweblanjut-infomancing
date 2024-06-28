<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $fillable = ['lokasi_id', 'user_id', 'komentar'];

    public function lokasi()
    {
        return $this->belongsTo(lokasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
