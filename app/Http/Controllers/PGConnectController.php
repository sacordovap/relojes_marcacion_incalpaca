<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PGConnectController extends Controller
{
    public function testPostgreSQLConnection()
    {
        try {
            // Verificar la conexión
            DB::connection('pgsql')->getPdo();

            // Obtener el nombre de la base de datos configurada en el archivo .env
            $databaseName = config('database.connections.' . config('database.default') . '.database');

            // Obtener las tablas de la base de datos
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");

            // Imprimir las tablas
            foreach ($tables as $table) {
                echo $table->table_name . "<br>";
            }

            return "La conexión a PostgreSQL está funcionando correctamente. Tablas existentes: " . implode(', ', array_column($tables, 'table_name'));
        } catch (\Exception $e) {
            return "Error de conexión a PostgreSQL: " . $e->getMessage();
        }
    }

    public function showMarcaciones()
    {
        try {
            DB::connection('pgsql');
            // Obtener los primeros 10 registros de la tabla "marcaciones" en PostgreSQL
            $marcaciones = DB::table('marcaciones')->take(10)->get();

            // Pasar los datos a la vista
            return view('marcaciones.postgres.showMarcaciones', ['marcaciones' => $marcaciones]);
        } catch (\Exception $e) {
            return "Error al obtener los datos de la tabla marcaciones: " . $e->getMessage();
        }
    }


}
