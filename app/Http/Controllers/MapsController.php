<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\puntos_gps;

class MapsController extends Controller
{
    public function getData()
    {
        return response()->json(['data' => puntos_gps::all()]);
    }
}
