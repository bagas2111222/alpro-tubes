<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Penjualan;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'penjualan_id',
        'harga',
        'jumlah_barang',
        'total_harga'
    ];
    
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
    public function barang()
{
    return $this->belongsTo(Barang::class, 'barang_id', 'id');
}

}
