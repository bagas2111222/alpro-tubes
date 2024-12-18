<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Penjualan;
use App\Models\Detail;

class BarangController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        // Check if the authenticated user's role is 'admin'
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $barang = Barang::all();

        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('stok', compact('barang'));
    }
    public function tambah(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
        ]);
    
        Barang::create($validatedData);
    
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($validatedData);

        return response()->json(['success' => true]);
    }
    public function destroy(Barang $barang)
{
    try {
        $barang->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}



    public function penjualan()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        // Check if the authenticated user's role is 'admin'
        if (auth()->user()->role !== 'kasir') {
            abort(403, 'Unauthorized access.');
        }
        $barang = Barang::all();
        return view('penjualan', compact('barang'));

    }

    // ini gpt dikit
    public function showSelected(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
    }

    // Check if the authenticated user's role is 'admin'
    if (auth()->user()->role !== 'kasir') {
        abort(403, 'Unauthorized access.');
    }
    // Mengambil barang terpilih dari request
    $selectedItems = json_decode($request->selectedItems, true);

    // Buat satu penjualan saja untuk semua barang
    $penjualan = Penjualan::create([
        'tgl_penjualan' => Carbon::today(),
        'pembayaran' => 'pending',
        'total_harga' => 'pending',
        'status' => 'pending',
    ]);

    // Ambil data barang dari database termasuk diskonnya
    $barangData = [];
    foreach ($selectedItems as $item) {
        $barang = Barang::with('disc')->where('nama', $item['nama'])->first();

        // Default diskon 0
        $discRate = 0;

        // Cek apakah diskon aktif berdasarkan start_time dan end_time
        if ($barang && $barang->disc) {
            $now = Carbon::now();
            $startTime = Carbon::parse($barang->disc->start_time);
            $endTime = Carbon::parse($barang->disc->end_time);

            // Cek apakah waktu sekarang di antara start_time dan end_time
            if ($now->between($startTime, $endTime)) {
                $discRate = $barang->disc->disc_rate;
            }
        }

        // Simpan detail barang dengan penjualan_id yang sama
        Detail::create([
            'barang_id' => $barang->id, // Sesuai kolom database
            'penjualan_id' => $penjualan->id, // Penjualan yang sama
            'harga' => $barang->harga_jual,
            'jumlah_barang' => $item['jumlah'],
            'total_harga' => $barang->harga_jual * $item['jumlah'],
        ]);

        $barangData[] = [
            'penjualan_id' => $penjualan->id,
            'nama' => $barang->nama,
            'harga' => $barang->harga_jual,
            'jumlah' => $item['jumlah'],
            'disc_rate' => $discRate, // Diskon aktif
            'total_harga' => $barang->harga_jual * $item['jumlah'], // Total harga sebelum diskon
            'total_harga_diskon' => ($barang->harga_jual * $item['jumlah']) * (1 - ($discRate / 100)), // Setelah diskon
        ];
        
    }
    $totalHarga = array_sum(array_map(function ($item) {
        return $item['harga'] * $item['jumlah'];
    }, $barangData));
    
    $totalHargaSetelahDiskon = array_sum(array_map(function ($item) {
        return $item['total_harga_diskon'];
    }, $barangData));

    // Kirim data ke view
    return view('table-barang', compact('barangData', 'totalHarga', 'totalHargaSetelahDiskon', 'penjualan'));
}



}