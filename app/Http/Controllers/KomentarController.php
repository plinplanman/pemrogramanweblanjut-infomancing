<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function index(Request $request)
    {
        $filter_nama_lokasi = $request->input('filter_nama_lokasi');
        $filter_nama_user = $request->input('filter_nama_user');
        $user = Auth::user();

        // Ambil semua lokasi yang terkait dengan komentar untuk dropdown
        $lokasiIds = Komentar::distinct()->pluck('lokasi_id');
        $allLokasis = Lokasi::whereIn('id', $lokasiIds)->get(['id', 'nama_lokasi']);

        // Ambil semua pengguna yang terkait dengan komentar untuk dropdown
        $userIds = Komentar::distinct()->pluck('user_id');
        $allUsers = User::whereIn('id', $userIds)->get(['id', 'name']);

        // Inisialisasi query untuk komentar
        $komentarsQuery = Komentar::query();

        if ($user->role == 'adminpusat') {
            // Admin pusat dapat melihat semua komentar
            $komentars = $komentarsQuery;
        } elseif ($user->role == 'mitra') {
            // Mitra hanya dapat melihat komentar yang mereka buat
            $komentars = $komentarsQuery->where('user_id', $user->id);
        }

        if ($filter_nama_lokasi) {
            // Dapatkan lokasi dengan nama yang diberikan
            $lokasi = Lokasi::where('nama_lokasi', $filter_nama_lokasi)->first();
            if ($lokasi) {
                // Filter komentar berdasarkan lokasi_id
                $komentars->where('lokasi_id', $lokasi->id);
            } else {
                $komentars = $komentars->whereNull('lokasi_id'); // Menghasilkan koleksi kosong
            }
        }

        if ($filter_nama_user) {
            // Dapatkan user dengan nama yang diberikan
            $userFilter = User::where('name', $filter_nama_user)->first();
            if ($userFilter) {
                // Filter komentar berdasarkan user_id
                $komentars->where('user_id', $userFilter->id);
            } else {
                $komentars = $komentars->whereNull('user_id'); // Menghasilkan koleksi kosong
            }
        }

        // Dapatkan komentar berdasarkan filter yang diterapkan
        $komentars = $komentars->with('lokasi', 'user')->get();

        return view('komentars.index', compact('komentars', 'allLokasis', 'allUsers'));
    }

    public function create(Request $request)
    {
        $lokasis = Lokasi::all();
        $lokasi_id = $request->input('lokasi_id'); // Get the selected lokasi_id from the request
        return view('komentars.create', compact('lokasis', 'lokasi_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'komentar' => 'required|string',
        ]);

        $user_id = Auth::id(); // Mendapatkan ID pengguna yang sedang login

        Komentar::create([
            'lokasi_id' => $request->lokasi_id,
            'user_id' => $user_id,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('komentars.index')->with('success', 'Komentar berhasil ditambahkan');
    }

    public function edit($id)
    {
        $komentar = Komentar::findOrFail($id);
        $lokasis = Lokasi::all();
        $name = User::find($komentar->user_id)->name; // Inisialisasi variabel $name dengan nama pengguna

        return view('komentars.edit', compact('komentar', 'lokasis', 'name')); // Melewatkan variabel $name ke view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi_id' => 'required|integer|min:1|exists:lokasis,id',
            'user_id' => 'required|integer|min:1|exists:users,id',
            'komentar' => 'required|string|max:255',
        ]);

        $komentar = Komentar::findOrFail($id);
        $komentar->lokasi_id = $request->get('lokasi_id');
        $komentar->user_id = $request->get('user_id');
        $komentar->komentar = $request->get('komentar');
        $komentar->save();

        return redirect()->route('komentars.index')->with('sukses', 'Komentar diubah.');
    }

    public function destroy(Komentar $komentar)
    {
        $komentar->delete();

        return redirect()->route('komentars.index')->with('sukses', 'Komentar dihapus.');
    }
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $userRole = Auth::user()->role;
            if (!in_array($userRole, ['adminpusat', 'mitra'])) {
                return response()->view('errors.access_denied', [], 403);
            }
            return $next($request);
        });
    }
}
