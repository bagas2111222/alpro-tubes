<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
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
        $user = User::all();
        return view('employee', compact('user'));

    }
    public function tambah(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'required|string',
        ]);

        // Simpan data ke database dengan hashing password
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Enkripsi password
            'role' => $validatedData['role'],
        ]);

        return response()->json(['success' => true]);
    }
    public function edit(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email,' . $id, // Ignore current user's email
            'password' => 'nullable',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($id);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // Update password jika ada input baru
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    public function hapus($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }


}
