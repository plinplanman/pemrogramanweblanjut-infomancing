@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4>{{ __('Info Lokasi Memancing') }}</h4>
                </div>
                <div class="card-body">
                    <div class="pull-right mb-3">
                        <a class="btn btn-primary" href="{{ url()->previous() }}"> Kembali</a>
                    </div>
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet and Geocoder Scripts and Styles -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<!-- Leaflet.fullscreen Plugin Scripts and Styles -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
<script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>

<!-- Leaflet.timeline Plugin Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-timeline/dist/leaflet.timeline.css" />
<script src="https://unpkg.com/leaflet-timeline/dist/leaflet.timeline.js"></script>

<!-- Leaflet.heat Plugin Scripts -->
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

<!-- Leaflet-routing-machine Plugin Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    // Membuat peta dan mengatur tampilan awal di Jepara, Indonesia
    var map = L.map('map', {
        touchZoom: true, // Mengaktifkan zoom dengan sentuhan
        dragging: true, // Mengaktifkan dragging
        fullscreenControl: true, // Mengaktifkan kontrol fullscreen
        fullscreenControlOptions: { // Opsi kontrol fullscreen
            position: 'topleft'
        }
    }).fitWorld().setView([-6.5926, 110.6781], 13);

    // Menambahkan layer tile dari OpenStreetMap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

// Menambahkan marker ke peta untuk setiap lokasi
@foreach($lokasis as $lokasi)
    @php
        $coordinates = explode(',', $lokasi->koordinat);
        $latitude = $coordinates[0];
        $longitude = $coordinates[1];
        $imagePath = asset('uploads/imgCover/' . $lokasi->gambar_lokasi);
    @endphp
    L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map)
        .bindPopup('<h3><b>{{ $lokasi->nama_lokasi }}</h3></b><b>{{ $lokasi->koordinat }}</b><br>{{ $lokasi->deskripsi }}<br><br><img src="{{ $imagePath }}" alt="Gambar Lokasi" style="width:150px;height:150px;object-fit:cover;"><br><h5><a href="{{ route('komentars.create', ['lokasi_id' => $lokasi->id]) }}">Tambah komentar</a><br><a href="{{ route('pesanans.create', ['lokasi_id' => $lokasi->id]) }}">Buat pesanan</a><br><a href="{{ route('lokasi.detail', ['id' => $lokasi->id]) }}">Lihat Detail</a></h5>');
@endforeach


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

    // Data dummy untuk timeline (harus diganti dengan data asli Anda)
    var timelineData = [
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [110.6781, -6.5926]
            },
            "properties": {
                "time": "2023-01-01T00:00:00Z"
            }
        }
        // Tambahkan data lainnya sesuai kebutuhan
    ];

    var timelineLayer = L.timeline(timelineData, {
        getInterval: function(feature) {
            return {
                start: new Date(feature.properties.time),
                end: new Date(feature.properties.time)
            };
        }
    }).addTo(map);

    // Menambahkan kontrol timeline
    var timelineControl = L.timelineSliderControl({
        formatOutput: function(date) {
            return new Date(date).toString();
        }
    });
    map.addControl(timelineControl);
    timelineControl.addTimelines(timelineLayer);

    // Data dummy untuk heatmap (harus diganti dengan data asli Anda)
    var heatData = [
        [-6.5926, 110.6781, 0.5]
        // Tambahkan data lainnya sesuai kebutuhan
    ];

    var heatLayer = L.heatLayer(heatData, {radius: 25}).addTo(map);

    // Menambahkan routing machine dengan rute dummy
    L.Routing.control({
        waypoints: [
            L.latLng(-6.5926, 110.6781),
            L.latLng(-6.6, 110.68)
        ]
    }).addTo(map);
</script>
@endsection
