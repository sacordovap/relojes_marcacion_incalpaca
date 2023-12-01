<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Aquí puedes agregar la lógica necesaria para mostrar la página del dashboard
        return view('dashboard.index');
    }
}
