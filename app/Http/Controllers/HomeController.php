<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\lokasi; // Import the lokasi model

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $lokasis = lokasi::all(); // Fetch all lokasis from the database
        return view('home', compact('lokasis'));
    }
}
