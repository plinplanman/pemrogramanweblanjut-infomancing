@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Pesanan</h1>
    <form action="{{ route('pesanans.update', $pesanan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="lokasi_id">Lokasi</label>
            <select name="lokasi_id" id="lokasi_id" class="form-control">
                @foreach ($lokasis as $lokasi)
                    <option value="{{ $lokasi->id }}" {{ $lokasi->id == $pesanan->lokasi_id ? 'selected' : '' }}>
                        {{ $lokasi->nama_lokasi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="user_id">User</label>
            <input type="text" class="form-control" value="{{ $pesanan->user->name }}" disabled>
            <input type="hidden" name="user_id" value="{{ $pesanan->user_id }}">
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $pesanan->tanggal }}">
        </div>

        <div class="form-group">
            <label for="jumlah_pesanan">Jumlah Pesanan</label>
            <input type="number" name="jumlah_pesanan" id="jumlah_pesanan" class="form-control" value="{{ $pesanan->jumlah_pesanan }}">
        </div>

        <div class="form-group">
            <label for="kode_pesanan">Kode Pesanan</label>
            <input type="text" class="form-control" value="{{ $pesanan->kode_pesanan }}" disabled>
            <input type="hidden" name="kode_pesanan" value="{{ $pesanan->kode_pesanan }}">
        </div>

        <div class="form-group">
            <label for="status_pesanan">Status Pesanan</label>
            <select name="status_pesanan" id="status_pesanan" class="form-control">
                <option value="Diproses" {{ $pesanan->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ $pesanan->status_pesanan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control">{{ $pesanan->catatan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
