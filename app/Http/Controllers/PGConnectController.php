<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PGConnectController extends Controller
{
    public function testPostgreSQLConnection()
    {
        try {
            // Verificar la conexión
            DB::connection()->getPdo();

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
}
