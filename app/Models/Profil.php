<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $table = 'profils'; // ✅ penting: biar Laravel tahu nama tabel sebenarnya
    protected $fillable = ['user_id', 'alamat', 'telepon'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
