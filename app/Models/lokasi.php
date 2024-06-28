<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lokasi',
        'deskripsi',
        'gambar_lokasi',
        'koordinat',
        'slug',
        'harga',
        'role',
    ];

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
