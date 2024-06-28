<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $pesanans = Pesanan::query();

    if ($user->role == 'adminpusat') {
        // Adminpusat: fetch all orders
        $pesanans = $pesanans->with('lokasi', 'user')->get();
    } elseif ($user->role == 'mitra') {
        // Mitra: fetch orders where lokasi_id matches the user's associated lokasi
        $pesanans = $pesanans->whereHas('lokasi', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('lokasi', 'user')->get();
    } elseif ($user->role == 'pengunjung') {
        // Pengunjung: fetch orders where user_id matches the logged-in user
        $pesanans = $pesanans->where('user_id', $user->id)->with('lokasi', 'user')->get();
    } else {
        // If user role is not recognized, return an empty collection
        $pesanans = collect();
    }

    return view('pesanans.index', compact('pesanans'));
}

    public function create(Request $request)
    {
        $lokasis = Lokasi::all();
        $users = User::all();
        $lokasi_id = $request->input('lokasi_id');
        return view('pesanans.create', compact('lokasis', 'users', 'lokasi_id'));
    }

    public function store(Request $request)
    {
        // Lakukan validasi data
        $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jumlah_pesanan' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        // Temukan objek lokasi berdasarkan ID yang diberikan
        $lokasi = Lokasi::findOrFail($request->lokasi_id);

        // Create a new instance of Pesanan model
        $pesanan = new Pesanan();

        // Generate kode pesanan
        $kode_pesanan = $this->generateKodePesanan();

        // Assign the values from the request
        $pesanan->lokasi_id = $request->lokasi_id;
        $pesanan->user_id = $request->user_id;
        $pesanan->tanggal = $request->tanggal;
        $pesanan->jumlah_pesanan = $request->jumlah_pesanan;
        $pesanan->catatan = $request->catatan;
        $pesanan->kode_pesanan = $kode_pesanan; // Simpan kode pesanan ke dalam model

        // Ambil harga dari objek lokasi yang ditemukan
        $harga_lokasi = $lokasi->harga;

        // Hitung total harga
        $total_harga = $harga_lokasi * $request->jumlah_pesanan;

        // Simpan total harga ke dalam model Pesanan
        $pesanan->total_harga = $total_harga;

        // Simpan data ke database
        $pesanan->save();

        // Redirect kembali atau ke mana pun Anda inginkan setelah menyimpan data
        return redirect()->route('pesanans.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    // Fungsi untuk generate kode pesanan
    private function generateKodePesanan()
    {
        $now = now();
        $kode = $now->format('YmdHis') . mt_rand(10000, 99999);
        return $kode;
    }

    public function show(Pesanan $pesanan)
    {
        return view('pesanans.detail', compact('pesanan'));
    }

    public function edit(Pesanan $pesanan)
    {
        $lokasis = Lokasi::all();
        $users = User::all();
        return view('pesanans.edit', compact('pesanan', 'lokasis', 'users'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jumlah_pesanan' => 'required|integer|min:1',
            'status_pesanan' => 'required|in:Diproses,Dikirim,Selesai,Dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $lokasi = Lokasi::find($request->lokasi_id);
        $total_harga = $request->jumlah_pesanan * $lokasi->harga;

        $pesanan->update([
            'lokasi_id' => $request->lokasi_id,
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'jumlah_pesanan' => $request->jumlah_pesanan,
            'total_harga' => $total_harga,
            'status_pesanan' => $request->status_pesanan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pesanans.index')->with('success', 'Pesanan updated successfully.');
    }

    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();
        return redirect()->route('pesanans.index')->with('success', 'Pesanan deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_pesanan = $request->input('status_pesanan');
        $pesanan->save();

        return redirect()->route('pesanans.index')->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function showDetail($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return view('pesanans.detail', compact('pesanan')); // Sertakan variabel $komentars dalam pemanggilan view
    }
    
}

