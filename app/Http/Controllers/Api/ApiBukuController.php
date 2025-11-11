<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class ApiBukuController extends Controller
{
    // GET /api/buku
    public function index()
    {
        return response()->json(Buku::all());
    }

    // POST /api/buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'penerbit' => 'required',
        ]);

        $buku = Buku::create($validated);
        return response()->json($buku, 201);
    }

    // GET /api/buku/{id}
    public function show(Buku $buku)
    {
        return response()->json($buku);
    }

    // PUT /api/buku/{id}
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'penerbit' => 'required',
        ]);

        $buku->update($validated);
        return response()->json($buku);
    }

    // DELETE /api/buku/{id}
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
