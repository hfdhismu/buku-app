<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'tahun_terbit',
        'penerbit',
        'user_id',
    ];

    // Relasi: setiap buku dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjam()
    {
        return $this->belongsToMany(User::class, 'buku_user')
                ->withPivot('tanggal_pinjam', 'tanggal_kembali')
                ->withTimestamps();
    }

}
