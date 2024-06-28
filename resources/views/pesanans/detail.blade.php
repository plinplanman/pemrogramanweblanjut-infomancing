<!-- resources/views/pesanans/detail.blade.php -->
@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Detail Pesanan</h1>

    <div class="card mt-3">
        <div class="card-header">
            Pesanan ID: {{ $pesanan->id }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Lokasi: {{ $pesanan->lokasi->nama_lokasi }}
                <a href="{{ route('lokasi.detail', $pesanan->lokasi->id) }}" class="btn btn-warning btn-sm"><b>Lihat Detail Lokasi</b></a>

            </h5>
            <p class="card-text">User: {{ $pesanan->user->name }}</p>
            <p class="card-text">Tanggal: {{ $pesanan->tanggal }}</p>
            <p class="card-text">Jumlah Pesanan: {{ $pesanan->jumlah_pesanan }}</p>
            <p class="card-text">Total Harga: Rp.{{ $pesanan->total_harga }}</p>
            <p class="card-text">Waktu Pemesanan: {{ $pesanan->created_at }}</p>
            <p class="card-text">Terakhir Diubah: {{ $pesanan->updated_at }}</p>
            <p class="card-text">Status Pesanan: {{ $pesanan->status_pesanan }}</p>
            <p class="card-text">Catatan: {{ $pesanan->catatan }}</p>

            <a href="{{ route('pesanans.index') }}" class="btn btn-primary">Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</div>
@endsection
