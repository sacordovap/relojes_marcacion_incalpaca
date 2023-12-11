<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Obtener los primeros 10 registros de la tabla "marcaciones" en SQL Server
            // $marcaciones = DB::connection('sqlsrv')->table('marcaciones')->take(10)->get();

            // Retornar la vista con los datos
            $relojes = DB::connection('sqlsrv')->table('zkRelInfo')->get();
            // return view('marcaciones.sqlserver.showMarcaciones', ['marcaciones' => $marcaciones]);
            return view('dashboard.index',  ['relojes' => $relojes]);
        } catch (\Exception $e) {
            return "Error al obtener los datos de la tabla marcaciones: " . $e->getMessage();
        }
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

    public function updateInfo()
    {
        try {
            $marcadores = DB::connection('sqlsrv')->table('zkRelInfo')->get();
            return view('dashboard.indexUpMark', ['marcadores' => $marcadores]);
        } catch (\Exception $e) {
            return "Error al obtener los datos de la tabla: " . $e->getMessage();
        }
    }
}
