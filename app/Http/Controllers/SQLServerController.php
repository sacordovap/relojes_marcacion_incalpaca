<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PDO;

class SQLServerController extends Controller
{
    public function checkConnection()
    {

        try {
            // Configuración de la conexión
            $config = [
                'driver'   => 'sqlsrv',
                'host'     => env('DB_HOST'),
                'port'     => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset'  => 'utf8',
                'prefix'   => '',
            ];

            // Intentar la conexión
            $connection = new PDO(
                "sqlsrv:Server={$config['host']},{$config['port']};Database={$config['database']}",
                $config['username'],
                $config['password']
            );

            // La conexión fue exitosa, muestra un mensaje
            return 'Conexión exitosa a la base de datos.';
        } catch (\Exception $e) {
            // La conexión falló, muestra un mensaje de error
            return 'Error al conectar a la base de datos: ' . $e->getMessage();
        }

        // try {
        //     DB::connection('sqlsrv')->getPdo();

        //     // Obtener el nombre de la base de datos configurada en el archivo .env
        //     $databaseName = config('database.connections.' . config('database.default') . '.database');

        //     // Obtener las tablas de la base de datos
        //     $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_catalog = ?", [$databaseName]);

        //     // Imprimir las tablas
        //     foreach ($tables as $table) {
        //         echo $table->table_name . "<br>";
        //     }

        //     return "La conexión a SQL Server está funcionando correctamente. Tablas existentes: " . implode(', ', array_column($tables, 'table_name'));
        // } catch (\Exception $e) {
        //     return "Error de conexión a SQL Server: " . $e->getMessage();
        // }
    }
    public function showMarcaciones()
    {
        try {
            // Obtener los primeros 10 registros de la tabla "marcaciones" en SQL Server
            // $marcaciones = DB::connection('sqlsrv')->table('marcaciones')->take(10)->get();

            // Retornar la vista con los datos
            $marcaciones = DB::connection('sqlsrv')->table('marcaciones')->take(10)->get();
            // return view('marcaciones.sqlserver.showMarcaciones', ['marcaciones' => $marcaciones]);
            return view('marcaciones.sqlserver.showMarcaciones', compact('marcaciones'));
        } catch (\Exception $e) {
            return "Error al obtener los datos de la tabla marcaciones: " . $e->getMessage();
        }
    }
}
