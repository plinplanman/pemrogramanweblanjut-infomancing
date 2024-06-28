@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-lg-12 d-flex justify-content-between align-items-center">
            <h2>Komentar List</h2>
            <div>
                <a class="btn btn-success" href="{{ route('komentars.create') }}">Buat Komentar</a>
                <a href="{{ route('map') }}" class="btn btn-primary">Lihat Map</a>
            </div>
        </div>
    </div>

    <!-- Filter form -->
    <form action="{{ route('komentars.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="filter-nama-lokasi" class="mr-2">Filter berdasarkan Nama Lokasi:</label>
            <select name="filter_nama_lokasi" id="filter-nama-lokasi" class="form-control">
                <option value="">Pilih Lokasi</option>
                @foreach ($allLokasis as $lokasi)
                    <option value="{{ $lokasi->nama_lokasi }}">{{ $lokasi->nama_lokasi }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter Lokasi</button>
        </div>

        <div class="form-group mr-2">
            <label for="filter-nama-user" class="mr-2">Filter berdasarkan Nama Pengguna:</label>
            <select name="filter_nama_user" id="filter-nama-user" class="form-control">
                <option value="">Pilih Pengguna</option>
                @foreach ($allUsers as $user)
                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter User</button>
        </div>
        <a href="{{ route('komentars.index') }}" class="btn btn-secondary">Tampilkan Semua Komentar</a>
    </form>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lokasi Name</th>
                <th>User Name</th>
                <th>Komentar</th>
                <th>Ditambahkan</th>
                <th>Diubah</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($komentars as $komentar)
            <tr>
                <td>{{ $komentar->lokasi->nama_lokasi }}</td>
                <td>{{ $komentar->user->name }}</td>
                <td>{{ $komentar->komentar }}</td>
                <td>{{ $komentar->created_at }}</td>
                <td>{{ $komentar->updated_at }}</td>
                <td>
                    <form action="{{ route('komentars.destroy', $komentar->id) }}" method="POST">
                        <a class="btn btn-primary" href="{{ route('komentars.edit', $komentar->id) }}">Edit</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
