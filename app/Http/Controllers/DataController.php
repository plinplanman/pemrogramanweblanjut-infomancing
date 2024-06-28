<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CentrePoint;
use App\Models\lokasi;
use Illuminate\Http\Request;

class DataController extends Controller
{
   
    public function centrepoint()
    {
        // Method ini untuk menampilkan data centrepoint atau koordinat
        // ke dalam datatables kita juga menambahkan column untuk menampilkan button
        // action
        $centrepoint = CentrePoint::orderBy('created_at', 'DESC');
        return datatables()->of($centrepoint)
            ->addColumn('action', 'centrepoint.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->toJson();
    }

    public function lokasis()
    {
        // Method ini untuk menampilkan data dari tabel lokasis
        // ke dalam datatables kita juga menambahkan column untuk menampilkan button
        // action
        $lokasis = lokasi::orderBy('created_at','DESC');
        return datatables()->of($lokasis)
        ->addColumn('action','lokasi.action')
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->toJson();
    }
}