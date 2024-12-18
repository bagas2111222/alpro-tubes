<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detail;

class DetailController extends Controller
{
    public function index($id)
    {
        // Ambil data detail berdasarkan penjualan_id
        $detail = Detail::where('penjualan_id', $id)->get();

        // Kirim data ke view 'detail'
        return view('detail', compact('detail'));
    }
}
