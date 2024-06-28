<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Komentar;
use App\Models\pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;


class LokasiController extends Controller
{
    // Method untuk menampilkan semua lokasi
    public function index(Request $request)
    {
        $query = Lokasi::query();

        // Filter berdasarkan nama
        if ($request->has('search')) {
            $query->where('nama_lokasi', 'like', '%' . $request->search . '%');
        }

        // Urutan berdasarkan tanggal (terbaru atau terlama)
        if ($request->has('order_date')) {
            if ($request->order_date == 'latest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->order_date == 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        }

        // Urutan berdasarkan harga (termurah atau termahal)
        if ($request->has('order_price')) {
            if ($request->order_price == 'cheapest') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->order_price == 'expensive') {
                $query->orderBy('harga', 'desc');
            }
        }

        // Ambil data lokasi dari database
        $lokasis = $query->get();

        return view('lokasi.index', compact('lokasis'));
    }

    // Method untuk menampilkan halaman create
    public function create()
    {
        return view('lokasi.create');
    }

    public function store(Request $request)
    {
        // Lakukan validasi data termasuk validasi unik untuk nama_lokasi
        $this->validate($request, [
            'nama_lokasi' => 'required|unique:lokasis,nama_lokasi',
            'deskripsi' => 'required',
            'gambar_lokasi' => 'image|mimes:png,jpg,jpeg',
            'koordinat' => 'required',
            'harga' => 'nullable|integer' // Validasi harga sebagai integer
        ]);

        // Cek apakah ada entri dengan nama yang sama
        $existingLokasi = Lokasi::where('nama_lokasi', $request->input('nama_lokasi'))->first();
        if ($existingLokasi) {
            return response()->json(['success' => false, 'message' => 'Lokasi dengan nama ini sudah ada.'], 400);
        }

        $lokasi = new Lokasi;

        // Jika ada file gambar, simpan dan update nama file pada objek lokasi
        if ($request->hasFile('gambar_lokasi')) {
            $file = $request->file('gambar_lokasi');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move('uploads/imgCover/', $imageName);
            $lokasi->gambar_lokasi = $imageName;
        }

        // Isi atribut lokasi lainnya dengan data dari form
        $lokasi->nama_lokasi = $request->input('nama_lokasi');
        $lokasi->slug = Str::slug($request->nama_lokasi, '-');
        $lokasi->koordinat = $request->input('koordinat');
        $lokasi->deskripsi = $request->input('deskripsi');
        $lokasi->harga = $request->input('harga');

        // Assign user_id based on the authenticated user
        $lokasi->user_id = Auth::id();

        // Simpan objek lokasi ke database
        $lokasi->save();

        // Mengembalikan respon JSON dengan pesan sukses
        return response()->json(['success' => true, 'redirect' => route('lokasi.index')]);
    }

    // Method untuk menampilkan detail lokasi
    public function show($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('lokasi.show', compact('lokasi'));
    }

    // Method untuk menampilkan halaman edit
    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('lokasi.edit', compact('lokasi'));
    }

    // Method untuk mengupdate data lokasi
    public function update(Request $request, $id)
    {
        // Menjalankan validasi
        $this->validate($request, [
            'nama_lokasi' => 'required',
            'deskripsi' => 'required',
            'gambar_lokasi' => 'image|mimes:png,jpg,jpeg',
            'koordinat' => 'required',
            'harga' => 'nullable|' // Validasi harga sebagai integer
        ]);

        $lokasi = Lokasi::findOrFail($id);

        // Jika ada file gambar, hapus yang lama dan simpan yang baru
        if ($request->hasFile('gambar_lokasi')) {
            if (File::exists("uploads/imgCover/" . $lokasi->gambar_lokasi)) {
                File::delete("uploads/imgCover/" . $lokasi->gambar_lokasi);
            }

            $file = $request->file("gambar_lokasi");
            $timestamp = time();
            $filename = $timestamp . '_' . $file->getClientOriginalName();
            $file->move('uploads/imgCover/', $filename);
            $lokasi->gambar_lokasi = $filename;
        }

        // Lakukan proses update data
        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'deskripsi' => $request->deskripsi,
            'koordinat' => $request->koordinat,
            'slug' => Str::slug($request->nama_lokasi, '-'),
            'gambar_lokasi' => $lokasi->gambar_lokasi,
            'harga' => $request->harga, // Tambahkan harga
        ]);

        // Redirect ke halaman index lokasi
        return redirect()->route('lokasi.index')->with('success', 'Data berhasil diupdate');
    }

    // Method untuk menghapus data lokasi
    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        if (File::exists("uploads/imgCover/" . $lokasi->gambar_lokasi)) {
            File::delete("uploads/imgCover/" . $lokasi->gambar_lokasi);
        }
        $lokasi->delete();
        return redirect()->route('lokasi.index')->with('success', 'Data berhasil dihapus');
    }

    // Method untuk menampilkan peta dengan semua lokasi
    public function showMap()
    {
        // Assuming you are fetching locations from a database
        $lokasis = Lokasi::all();
        return view('map', compact('lokasis'));
    }
    

    // Method untuk menampilkan detail lokasi beserta komentar
    public function showDetail($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $komentars = Komentar::where('lokasi_id', $id)->get(); // Ambil komentar yang memiliki lokasi_id yang sama
        return view('lokasi.detail', compact('lokasi', 'komentars')); // Sertakan variabel $komentars dalam pemanggilan view
    }

    // Method untuk menampilkan pesanan berdasarkan lokasi_id
    public function pesananLokasi($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $pesanan = Pesanan::where('lokasi_id', $id)->get();
        return view('lokasi.pesananlokasi', compact('lokasi', 'pesanan'));
    }
}
