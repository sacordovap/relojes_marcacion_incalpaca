<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Aquí puedes agregar la lógica necesaria para mostrar la página del dashboard
        return view('dashboard.index');
    }

    public function seleccionarDispositivo(Request $request)
    {
        $selectedDeviceKey = $request->input('selected_device', 'zk'); // Por defecto, selecciona 'zk'
        return redirect()->route('dashboard', ['selected_device' => $selectedDeviceKey]);
    }

    public function indexUpMark(Request $request)
    {
        // Realiza las actualizaciones aquí

        // Redirige de vuelta al panel de control
        $redirect = $request->input('redirect', 'dashboard');
        return redirect()->route($redirect)->with('success', 'Actualizaciones completadas exitosamente.');
    }
}
