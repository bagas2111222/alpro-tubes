<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Detail;
use App\Models\Barang;
use Carbon\Carbon;

class PenjualanController extends Controller
{

    public function bayar(Request $request, $id)
{
    $penjualan = Penjualan::findOrFail($id);

    // Update status pembayaran
    $penjualan->update([
        'pembayaran' => $request->pembayaran,
        'total_harga' => $request->total_harga,
        'status' => $request->status,
    ]);

    // Ambil semua detail penjualan berdasarkan id penjualan
    $details = Detail::where('penjualan_id', $id)->get();

    // Loop setiap barang untuk mengurangi stok
    foreach ($details as $detail) {
        $barang = Barang::findOrFail($detail->barang_id);

        // Kurangi jumlah stok barang
        $barang->stok = $barang->stok - $detail->jumlah_barang;

        // Simpan update stok ke database
        $barang->save();
    }

    return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dan stok barang diperbarui!']);
}
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        // Check if the authenticated user's struktur is 'admin'
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        // Mengambil data penjualan hanya dengan status 'selesai'
        $penjualan = Penjualan::where('status', 'selesai')->get();

        // Mengirim data ke view 'history'
        return view('history', compact('penjualan'));
    }


}
