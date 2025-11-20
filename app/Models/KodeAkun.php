<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KodeAkun extends Model
{
    protected $table = 'kode_akun';

    protected $fillable = [
        'user_id',
        'kode_akun',
        'nama_akun',
        'kategori_akun',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
