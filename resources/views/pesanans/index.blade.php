<!-- resources/views/pesanans/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pesanan</h1>
    <a href="{{ route('pesanans.create') }}" class="btn btn-primary">Buat Pesanan Baru</a>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-3">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lokasi</th>
                <th>User</th>
                <th>Tanggal</th>
                <th>Jumlah Pesanan</th>
                <th>Total Harga</th>
                <th>Kode Pesanan</th>
                <th>Waktu Pemesanan</th>
                <th>Terakhir Diubah</th>
                <th>Status Pesanan</th>
                <th>Ubah Status Pesanan</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanans as $pesanan)
                <tr>
                    <td>{{ $pesanan->id }}</td>
                    <td>{{ $pesanan->lokasi->nama_lokasi }}</td>
                    <td>{{ $pesanan->user->name }}</td>
                    <td>{{ $pesanan->tanggal }}</td>
                    <td>{{ $pesanan->jumlah_pesanan }} orang</td>
                    <td>Rp.{{ $pesanan->total_harga }}</td>
                    <td>{{ $pesanan->kode_pesanan }}</td>
                    <td>{{ $pesanan->created_at }}</td>
                    <td>{{ $pesanan->updated_at }}</td>
                    <td>{{ $pesanan->status_pesanan }}</td>
                    <td>
                        @if (auth()->user()->role == 'adminpusat' || auth()->user()->role == 'mitra')
                            <form action="{{ route('pesanans.updateStatus', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="diproses">
                                <button type="submit" class="btn btn-warning">Diproses</button>
                            </form>
                            <form action="{{ route('pesanans.updateStatus', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="selesai">
                                <button type="submit" class="btn btn-success">Selesai</button>
                            </form>
                            <form action="{{ route('pesanans.updateStatus', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="dibatalkan">
                                <button type="submit" class="btn btn-danger">Batalkan</button>
                            </form>
                        @elseif (auth()->user()->role == 'pengunjung')
                            <form action="{{ route('pesanans.updateStatus', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="diproses">
                                <button type="submit" class="btn btn-warning">Diproses</button>
                            </form>
                            <form action="{{ route('pesanans.updateStatus', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_pesanan" value="dibatalkan">
                                <button type="submit" class="btn btn-danger">Batalkan</button>
                            </form>
                        @endif
                    </td>
                    
                    <td>{{ $pesanan->catatan }}</td>
                    <td>
                        <a href="{{ route('pesanans.show', $pesanan->id) }}" class="btn btn-primary">Detail Pesanan</a>
                        <a href="{{ route('pesanans.edit', $pesanan->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('pesanans.destroy', $pesanan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">Tidak ada pesanan yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
