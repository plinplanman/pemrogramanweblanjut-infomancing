<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Lokasi - Leaflet</title>
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
            height: 400px;
            width: 600px;
            max-width: 100%;
            max-height: 100%;
        }
        body {
            padding: 0;
            margin: 0;
        }
        #map {
            height: 100%;
            width: 100vw;
        }
        #form-container {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div id="map"></div>
<div id="form-container">
    <form id="lokasiForm" method="POST" enctype="multipart/form-data" action="{{ route('lokasi.update', $lokasi->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nama_lokasi">Nama Lokasi:</label>
            <input type="text" id="nama_lokasi" name="nama_lokasi" value="{{ $lokasi->nama_lokasi }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" required>{{ $lokasi->deskripsi }}</textarea>
        </div>
        <div class="form-group">
            <label for="gambar_lokasi">Gambar:</label>
            <input type="file" id="gambar_lokasi" name="gambar_lokasi" class="form-control-file">
        </div>
        <div class="form-group">
            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga', $lokasi->harga) }}" class="form-control" placeholder="Contoh: 30000">
        </div>
        <div class="form-group">
            <label for="user">Ditambahkan Oleh:</label>
            <input type="text" id="user" name="user" value="{{ $lokasi->user->name }}" class="form-control" readonly>
            <input type="hidden" name="user_id" value="{{ $lokasi->user_id }}">
        </div>
        <div class="form-group">
            <label for="koordinat">Koordinat:</label>
            <input type="text" id="koordinat" name="koordinat" value="{{ $lokasi->koordinat }}" class="form-control" readonly required>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    var map = L.map('map', {
        touchZoom: true,
        dragging: true
    }).setView([{{ $lokasi->koordinat }}], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([{{ $lokasi->koordinat }}]).addTo(map);

    map.on('click', function(e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('koordinat').value = e.latlng.lat + ", " + e.latlng.lng;
    });

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
