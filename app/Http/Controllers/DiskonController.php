<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disc;
use App\Models\Barang;

class DiskonController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }
    
        // Check if the authenticated user's struktur is 'admin'
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $diskon = Disc::all();
        $barang = Barang::all();
        $edit = Barang::all();

        $title = 'Delete Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('diskon', compact('diskon','barang','edit'));
    }
    public function tambah(Request $request)
    {
        $validatedData = $request->validate([
            'barang_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'disc_rate' => 'required|integer',
        ]);
    
        Disc::create($validatedData);
    
        return response()->json(['success' => true]);
    }
    public function destroy(Disc $diskon)
{
    try {
        $diskon->delete();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}
