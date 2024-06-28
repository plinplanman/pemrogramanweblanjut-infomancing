<!DOCTYPE html>
<html>
<head>
    <title>Edit Komentar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Komentar</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('komentars.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>waduh!</strong> ada yang salah dengan data yang kamu masukkan .<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('komentars.update', $komentar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>lokasi:</strong>
                    <select name="lokasi_id" class="form-control">
                        @foreach ($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ $komentar->lokasi_id == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
        <strong>User:</strong>
        <input type="text" class="form-control" value="{{ $name }}" readonly>
        <input type="hidden" name="user_id" value="{{ $komentar->user_id }}">
    </div>
</div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Komentar:</strong>
                    <textarea class="form-control" style="height:150px" name="komentar" placeholder="Komentar">{{ $komentar->komentar }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
