<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Disc;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga_jual',
        'harga_beli',
        'stok',
    ];
    public function disc()
    {
        return $this->hasOne(Disc::class, 'barang_id');
    }
    
}
