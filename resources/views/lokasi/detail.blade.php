@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Lokasi - Leaflet</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
    <!-- Leaflet Control Geocoder CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .leaflet-container {
            height: 500px;
            width: 100%;
        }
        #map-container {
            margin-top: 20px;
        }
        #table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ url()->previous() }}">Kembali</a>
        </div>
        <div class="col-md-8">
            <div id="map-container">
                <div id="map"></div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="table-container">
                <h3>Detail Lokasi</h3>
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">ID</th>
                            <td>{{ $lokasi->id }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nama</th>
                            <td>{{ $lokasi->nama_lokasi }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Koordinat</th>
                            <td>{{ $lokasi->koordinat }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Gambar</th>
                            <td><img src="{{ asset('uploads/imgCover/' . $lokasi->gambar_lokasi) }}" alt="{{ $lokasi->nama_lokasi }}" width="100"></td>
                        </tr>
                        <tr>
                            <th scope="row">Harga</th>
                            <td>Rp. {{ $lokasi->harga }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Deskripsi</th>
                            <td>{{ $lokasi->deskripsi }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Ditambahkan</th>
                            <td>{{ $lokasi->created_at }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Diperbarui</th>
                            <td>{{ $lokasi->updated_at }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Komentar</th>
                            <td> 
                                @foreach($komentars as $komentar)
                                    <b>{{ $komentar->user->name }}:</b>
                                    {{ $komentar->komentar }} <br>
                                    {{ $komentar->updated_at }} <br><br>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('komentars.create', ['lokasi_id' => $lokasi->id]) }}" class="btn btn-primary btn-sm">Tambah komentar</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Membuat peta dan mengatur tampilan awal di lokasi yang sudah ada
    var map = L.map('map').setView([{{ $lokasi->koordinat }}], 15);

    // Menambahkan layer tile dari OpenStreetMap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([{{ $lokasi->koordinat }}]).addTo(map);

    // Menambahkan popup ke marker dengan informasi nama, dan lokasi
    marker.bindPopup('<b>{{ $lokasi->nama_lokasi }}</b><br>Lokasi: {{ $lokasi->koordinat }}').openPopup();

    // Integrasi Leaflet Control Geocoder untuk pencarian lokasi
    L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        const bbox = e.geocode.bbox;
        const poly = L.polygon([
            bbox.getSouthEast(),
            bbox.getNorthEast(),
            bbox.getNorthWest(),
            bbox.getSouthWest()
        ]).addTo(map);
        map.fitBounds(poly.getBounds());
    })
    .addTo(map);
</script>
</body>
</html>
@endsection
