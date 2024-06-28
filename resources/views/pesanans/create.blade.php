@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Buat Pesanan Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pesanans.store') }}" method="POST" id="pesananForm">
                        @csrf
                        <div class="form-group">
                            <label for="lokasi_id" class="font-weight-bold">Lokasi</label>
                            <select class="form-control" name="lokasi_id" required>
                                @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" 
                                    {{ old('lokasi_id') == $lokasi->id || (isset($lokasi_id) && $lokasi_id == $lokasi->id) ? 'selected' : '' }}>
                                    {{ $lokasi->nama_lokasi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="kode_pesanan" id="kode_pesanan">

                        <div class="form-group">
                            <label for="tanggal" class="font-weight-bold">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pesanan" class="font-weight-bold">Jumlah Orang</label>
                            <input type="number" name="jumlah_pesanan" id="jumlah_pesanan" class="form-control" min="1" value="{{ old('jumlah_pesanan', 1) }}" oninput="validateInput()" required placeholder="jumlah orang">
                        </div>

                        <div class="form-group">
                            <label for="catatan" class="font-weight-bold">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="4">{{ old('catatan') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateInput() {
        var input = document.getElementById('jumlah_pesanan');
        if (input.value < 1) {
            input.value = ;
        }
    }

    // Generate Kode Pesanan
    function generateKodePesanan() {
        var now = new Date();
        var kode = now.getTime().toString().substr(-5) + Math.random().toString(36).substr(2, 5);
        document.getElementById('kode_pesanan').value = kode.toUpperCase();
    }

    // Panggil fungsi generate saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        generateKodePesanan();
    });
</script>
@endsection
