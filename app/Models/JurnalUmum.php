<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $fillable = [
        'user_id',
        'kas_id',
        'tanggal',
        'kodeakun_id',
        'keterangan',
        'debit',
        'kredit',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }

    public function kodeAkun()
    {
        return $this->belongsTo(KodeAkun::class, 'kodeakun_id');
    }
}
