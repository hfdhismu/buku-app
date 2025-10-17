<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Sesuai nama tabel di database
    protected $table = 'buku_user';

    // Kolom yang bisa diisi
    protected $fillable = [
        'buku_id',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    // Relasi ke Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
