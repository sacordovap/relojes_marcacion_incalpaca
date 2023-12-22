<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@php
    use Rats\Zkteco\Lib\ZKTeco;

    $resultados = [];

    $serverName = 'TPXSVBD02\CONTSQLDB,61865';
    $connectionInfo = ['Database' => env('DB_DATABASE'), 'UID' => env('DB_USERNAME'), 'PWD' => env('DB_PASSWORD')];
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $host = env('DB_HOST_PG');
    $port = env('DB_PORT_PG');
    $database = env('DB_DATABASE_PG');
    $username = env('DB_USERNAME_PG');
    $password = env('DB_PASSWORD_PG');

    $connect = pg_connect("host=$host port=$port user=$username password=$password dbname=$database");

    $hostSigo = env('DB_HOST_PG2');
    $portSigo = env('DB_PORT_PG2');
    $databaseSigo = env('DB_DATABASE_PG2');
    $usernameSigo = env('DB_USERNAME_PG2');
    $passwordSigo = env('DB_PASSWORD_PG2');

    $connectionString = "host=$hostSigo port=$portSigo dbname=$databaseSigo user=$usernameSigo password=$passwordSigo";

    // Conexión a PostgreSQL UserSigo

    foreach ($marcadores as $marcador) {
        $device2 = new ZKTeco($marcador->host, $marcador->port);
        echo '<h4>' . 'Conexión exitosa con el marcador ' . $marcador->numreloj . '</h4>';

        if (!$connect && !$conn) {
            if (!$connect) {
                $resultados[] = ['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()];
                die(json_encode(['error' => 'Error de conexión a la base de datos SQLSERVER', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
            } else {
                $resultados[] = ['error' => 'Error de conexión a la base de datos', 'details' => pg_last_error()];
                die(json_encode(['error' => 'Error de conexión a la base de datos Postgres', 'details' => pg_last_error()], JSON_PRETTY_PRINT));
            }
        } else {
            if ($device2->connect()) {
                //ACTIVAR EL DISABLE
                // $device2->disableDevice();

                // $serie = $device2->serialNumber();
                $users = $device2->getAttendance(); // Update this line
                insertarData($users, $conn, $marcador->numreloj, $connectionString, $connect, $device2);
                $resultados[] = ['message' => 'Inserción exitosa en la base de datos'];

                //ACTIVAR EL DISABLE
                // $device2->enableDevice();
                // $device2->clearAttendance();
                $device2->disconnect();
            } else {
                $device2->disconnect();
                $resultados[] = ['error' => 'No se pudo conectar al dispositivo'];
            }
        }
    }

    //CERRANDO CONEXION CON SQL SERVER

    sqlsrv_close($conn);

    //CERRANDO CONEXION CON PG
    pg_close($connect);

    // Comprobar resultados y mostrar alerta
    $success = true;
    $errorDetails = [];

    foreach ($resultados as $resultado) {
        if (isset($resultado['error'])) {
            $success = false;
            $errorDetails[] = $resultado['error'];
        }
    }

    if ($success) {
        // Inserción exitosa en todas las operaciones
        $message = 'Operaciones completadas con éxito.';
    } else {
        // Al menos una operación falló
        $message = 'Ocurrieron errores durante las operaciones.';
        $errorDetails = json_encode($errorDetails, JSON_PRETTY_PRINT);
    }

    // Almacenar el mensaje de éxito o error en la sesión
    session()->flash('success', $message);
    session()->flash('errorDetails', $errorDetails);

    function hoursToMinutes($hours)
    {
        $separatedDateTime = explode(' ', $hours);

        $separatedData = explode(':', $separatedDateTime[1]);
        $minutesInHours = $separatedData[0] * 60;
        $minutesInDecimals = $separatedData[1];
        $totalMinutes = $minutesInHours + $minutesInDecimals;

        return $totalMinutes;
    }

    // FUNCION QUE CONVIERTE LA FECHA EN UN SOLO NUMERO

    function dateToNumber($date)
    {
        $separatedData = explode('-', $date);
        $number = $separatedData[0] . $separatedData[1] . $separatedData[2];
        $separatedNumber = explode(' ', $number);
        return $separatedNumber[0];
    }

    function insertarData($users, $conn, $id, $connectionString, $connect, $device2)
    {
        $connSigo = pg_connect($connectionString);

        if (!$connSigo) {
            die('Error de conexión: ' . pg_last_error());
        }
        $nombreVista = 'perbol';

        // Consulta a la vista
        $querySigo = "SELECT * FROM $nombreVista";
        $resultSigo = pg_query($connSigo, $querySigo);

        if (!$resultSigo) {
            die('Error en la consulta: ' . pg_last_error());
        }

        // Obtener datos de la vista
        $rowsSigo = pg_fetch_all($resultSigo);

        // Cerrar la conexión
        pg_close($connSigo);

        // Crear un array para almacenar los valores a insertar
        $valuesToInsert = [];
        $valuesToInsertPG = [];

        foreach ($users as $user) {
            $mark_code = $user['id'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);

            //INFORMACION FALTANTE DEL USUARIO
            foreach ($rowsSigo as $row) {
                if ($row['percod'] == $mark_code) {
                    $mark_dni = $row['pernumdocide'];
                    $mark_numTarj = $row['pernumfotchk'];
                }
            }

            // Agregar los valores al array
            $valuesToInsert[] = "('$mark_code', $mark_date, $mark_minutes, $id,'$mark_numTarj','$mark_dni')";
            $valuesToInsertPG[] = "('$mark_code', $mark_date, $mark_minutes, $id)";
        }

        // Crear una transacción para SQL Server
        sqlsrv_begin_transaction($conn);
        echo '<h5>' . 'QUERY SQLSERVER' . '</h5>';
        try {
            $chunks = array_chunk($valuesToInsert, 500);

            foreach ($chunks as $chunk) {
                // Construir la consulta de bulk insert
                $bulkInsertQuery = 'INSERT INTO marcaciones (cod_trabajador, fecha_marcacion, hora_marcacion, numero_reloj, numero_tarjeta, dni) VALUES ' . implode(', ', $chunk);

                echo '' . $bulkInsertQuery . '<br>' . '<br>';

                // Ejecutar la consulta de bulk insert
                $result = sqlsrv_query($conn, $bulkInsertQuery);

                if (!$result) {
                    // Si hay un error, revertir la transacción y manejar el error
                    sqlsrv_rollback($conn);
                    // $device2->enableDevice();
                    $device2->disconnect();
                    die(json_encode(['error' => 'Error al insertar marcaciones en la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
                }
            }

            // Confirmar la transacción si todo está bien
            sqlsrv_commit($conn);
        } catch (Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir
            sqlsrv_rollback($conn);
            // $device2->enableDevice();
            $device2->disconnect();
            die(json_encode(['error' => 'Error al insertar marcaciones en la base de datos', 'details' => $e->getMessage()], JSON_PRETTY_PRINT));
        }

        echo '<h5>' . 'QUERY POSTGRES' . '</h5>';
        if (!empty($valuesToInsertPG)) {
            // Construir la consulta de bulk insert con ON CONFLICT
            $bulkInsertQueryPG = 'INSERT INTO marcaciones (cod_trabajador, fecha_marcacion, hora_marcacion, numero_reloj) VALUES ' . implode(', ', $valuesToInsertPG) . ' ON CONFLICT (cod_trabajador, fecha_marcacion, hora_marcacion) DO NOTHING';

            echo '' . $bulkInsertQueryPG . '<br>' . '<br>';

            // Ejecutar la consulta de bulk insert en PostgreSQL
            $resultPg = pg_query($connect, $bulkInsertQueryPG);

            if (!$resultPg) {
                die('Error al ejecutar la consulta: ' . pg_last_error($connect));
            }

            $estadoPg = pg_connection_status($connect);

            if ($estadoPg === PGSQL_CONNECTION_OK) {
                echo 'Conexión OK';
            } else {
                echo 'Se perdió la conexión';
            }
        }
    }

@endphp

@section('content')
    <div class="container mt-3">
        <!-- Verificar si hay un mensaje de éxito en la sesión -->
        @if ($success)
            <!-- Incluir SweetAlert desde la CDN -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

            <!-- Mostrar SweetAlert al cargar la página -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var valor = {!! json_encode($success) !!}; // Obtener el valor de PHP en JavaScript

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: '{{ session('success') }}',
                        showCancelButton: valor,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Acciones a realizar si se hace clic en 'Confirmar'
                            console.log('Se hizo clic en Confirmar');
                            // Realizar la redirección aquí
                            window.location.href = "{{ route('dashboard') }}";
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Acciones a realizar si se hace clic en 'Cancelar'
                            console.log('Se hizo clic en Cancelar');
                        }
                    });
                });
            </script>
        @endif

        <!-- Resto del contenido de la vista... -->

        <!-- Botón para regresar -->
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Regresar</a>
    </div>
@endsection
