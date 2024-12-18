<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disc extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'start_time',
        'end_time',
        'disc_rate',
    ];
    // public function barang()
    // {
    //     return $this->belongsTo(Barang::class, 'barang_id');
    // }
    public function barang()
{
    return $this->belongsTo(Barang::class, 'barang_id', 'id');
}

}
