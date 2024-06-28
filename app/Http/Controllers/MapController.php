<?php

namespace App\Http\Controllers;

use App\Models\CentrePoint;
use App\Models\lokasi;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        /**
         *  Pada method index kita mengambil single data dari tabel centrepoint
         *  Selanjutnya kita mengambil seluruh data dari tabel lokasi untuk menampilkan marker yang akan
         *  kita gtampilkan pada view map.blade 
         */
        $centrePoint = CentrePoint::get()->first();
        $lokasis = lokasi::get();
        return view('map',[
            'lokasis' => $lokasis,
            'centrePoint' => $centrePoint
        ]);
        //return dd($lokasis);
    }

    public function show($slug)
    {
        /**
         * Hampir sama dengam method index diatas
         * tapi disini kita hanya akan menampilkan single data saja untuk lokasi
         * yang kita pilih pada view map dan selanjutnya kita akan di arahkan 
         * ke halaman detail untuk melihat informasi lebih lengkap dari lokasi
         * yang kita pilih
         */
        $centrePoint = CentrePoint::get()->first();
        $lokasis = lokasi::where('slug',$slug)->first();
        return view('detail',[
            'centrePoint' => $centrePoint,
            'lokasis' => $lokasis
        ]);
    }
}