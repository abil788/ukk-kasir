<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'tanggal_registrasi',
    ];

    protected $casts = [
        'tanggal_registrasi' => 'date',
    ];

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}