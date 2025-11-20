<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';

    protected $fillable = [
        'user_id',
        'kodeakun_id',
        'tanggal',
        'jenis',
        'nominal',
        'keterangan',
        'saldo',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    public function kodeAkun()
    {
        return $this->belongsTo(KodeAkun::class, 'kodeakun_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class);
    }
}
