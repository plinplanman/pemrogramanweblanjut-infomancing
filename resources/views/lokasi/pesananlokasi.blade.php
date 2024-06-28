@extends('layouts.app')

@section('content')
<div class="container">

    @if ($pesanan->isEmpty())
        <p>Tidak ada pesanan untuk lokasi ini.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemesan</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pesanan</th>
                    <th>Total Harga</th>
                    <th>Kode Pesanan</th>
                    <th>Status Pesanan</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->jumlah_pesanan }}</td>
                        <td>{{ $item->total_harga }}</td>
                        <td>{{ $item->kode_pesanan }}</td>
                        <td>{{ $item->status_pesanan }}</td>
                        <td>{{ $item->catatan }}</td>
                        <td>
                            <a href="{{ route('lokasi.detail', $item->id) }}" class="btn btn-primary btn-sm">Detail</a>

                            <a href="{{ route('pesanans.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
