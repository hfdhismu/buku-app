<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔹 Tambahkan bagian ini di bawah
    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function buku()
    {
        return $this->hasMany(Buku::class);
    }

    public function bukuDipinjam()
    {
        return $this->belongsToMany(Buku::class, 'buku_user')
                ->withPivot('tanggal_pinjam', 'tanggal_kembali')
                ->withTimestamps();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
