<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PDO;

class SQLServerController extends Controller
{
    public function checkConnection()
    {
        try {
            DB::connection()->getPdo();

            // Obtener el nombre de la base de datos configurada en el archivo .env
            $databaseName = config('database.connections.' . config('database.default') . '.database');

            // Obtener las tablas de la base de datos
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_catalog = ?", [$databaseName]);

            // Imprimir las tablas
            foreach ($tables as $table) {
                echo $table->table_name . "<br>";
            }

            return "La conexión a SQL Server está funcionando correctamente. Tablas existentes: " . implode(', ', array_column($tables, 'table_name'));
        } catch (\Exception $e) {
            return "Error de conexión a SQL Server: " . $e->getMessage();
        }
    }
}
