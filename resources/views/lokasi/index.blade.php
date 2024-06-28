@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Daftar Lokasi</h1>
    <a href="{{ route('lokasi.create') }}" class="btn btn-primary mb-3">Tambah Lokasi Baru</a><br>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (Auth::user()->role == 'adminpusat')
    @endif
    <a href="{{ route('map') }}" class="btn btn-success mb-3">Lihat Map</a><br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Koordinat</th>
                <th>Gambar</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Ditambahkan Oleh</th>
                <th>Info</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lokasis as $lokasi)
                @if (Auth::user()->role == 'adminpusat' || Auth::id() == $lokasi->user_id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                        <td>{{ $lokasi->koordinat }}</td>
                        <td>
                            @if($lokasi->gambar_lokasi)
                                <img src="{{ asset('uploads/imgCover/' . $lokasi->gambar_lokasi) }}" alt="{{ $lokasi->nama_lokasi }}" width="100">
                            @else
                                Tidak ada gambar
                            @endif
                        </td>
                        <td>{{ $lokasi->deskripsi }}</td>
                        <td>Rp.{{ $lokasi->harga }}</td>
                        <td>{{ $lokasi->user->name }}</td>
                        <td>
                            <a href="{{ route('lokasi.pesananlokasi', $lokasi->id) }}" class="btn btn-success btn-sm">Pesanan Lokasi</a>
                            <a href="{{ route('lokasi.detail', $lokasi->id) }}" class="btn btn-primary btn-sm">Detail Lokasi</a>

                        </td>

                        <td>

                            @if (Auth::user()->role == 'adminpusat' || Auth::id() == $lokasi->user_id)
                                <a href="{{ route('lokasi.edit', $lokasi->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('lokasi.destroy', $lokasi->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

@endsection
