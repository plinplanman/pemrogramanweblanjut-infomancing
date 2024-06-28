@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Tambah Komentar</h2>
                    <a class="btn btn-light btn-sm" href="{{ url()->previous() }}">Kembali</a>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success mt-3">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Waduh!</strong> Ada yang salah.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('komentars.store') }}" method="POST">
                        @csrf
                        <!-- Hidden input to store the previous URL -->
                        <input type="hidden" name="previous_url" value="{{ url()->previous() }}">

                        <div class="form-group">
                            <label for="lokasi_id" class="font-weight-bold">Lokasi:</label>
                            <select class="form-control" name="lokasi_id" id="lokasi_id">
                                @foreach($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $lokasi_id) == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="komentar" class="font-weight-bold">Komentar:</label>
                            <textarea class="form-control" id="komentar" name="komentar" style="height:150px" placeholder="Tulis komentar Anda...">{{ old('komentar') }}</textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
