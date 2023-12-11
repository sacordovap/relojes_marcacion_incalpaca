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
        echo 'conecte marcador' . $marcador->numreloj . '<br>';
        if ($device2->connect()) {
            //ACTIVAR EL DISABLE
            // $device2->disableDevice();

            // $serie = $device2->serialNumber();
            $users = $device2->getAttendance(); // Update this line
            if (!$connect) {
                $resultados[] = ['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()];
                die(json_encode(['error' => 'Error de conexión a la base de datos Postgres', 'details' => pg_last_error()], JSON_PRETTY_PRINT));
            } else {
                insertarData($users, $conn, $marcador->numreloj, $connectionString);
                $resultados[] = ['message' => 'Inserción exitosa en la base de datos'];
            }
            if (!$conn) {
                die(json_encode(['error' => 'Error de conexión a la base de datos SQLSERVER', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
            } else {
                // insertarDataPG($users, $connect, $marcador->numreloj);
                $resultados[] = ['message' => 'Inserción exitosa en la base de datos'];
            }

            // Desconectar del dispositivoy RECONECTAR
            // $device2->enableDevice();
            // $device2->clearAttendance();
            $device2->disconnect();
        } else {
            $resultados[] = ['error' => 'No se pudo conectar al dispositivo'];
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

    function insertarData($users, $conn, $id, $connectionString)
    {
        echo 'QUERY SQLSERVER' . '<br>';
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

        foreach ($users as $user) {
            $mark_code = $user['id'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);

            //INSERCION PARA SQLSERVER
            foreach ($rowsSigo as $row) {
                if ($row['percod'] == $mark_code) {
                    $mark_dni = $row['pernumdocide'];
                    $mark_numTarj = $row['pernumfotchk'];
                }
            }
            // echo 'Percod: ' . $mark_code . ' DNI: ' . $mark_dni . ' Tarjeta: ' . $mark_numTarj . '<br>';
            $query = "INSERT INTO marcaciones (cod_trabajador, fecha_marcacion, hora_marcacion, numero_reloj,numero_tarjeta,dni)
          VALUES ('$mark_code', $mark_date, $mark_minutes, $id,'$mark_numTarj','$mark_dni')";

            echo '' . $query . '<br>';

            $result = sqlsrv_query($conn, $query);

            if (!$result) {
                // sqlsrv_close($conn);
                die(json_encode(['error' => 'Error al insertar marcaciones en la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
            }
        }
    }
    function insertarDataPG($users, $connect, $id)
    {
        echo 'QUERY POSTGRES' . '<br>';
        foreach ($users as $user) {
            $mark_code = $user['id'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);

            //INSERCION PARA POSTGRES
            $query = "INSERT INTO marcaciones (cod_trabajador,fecha_marcacion , hora_marcacion, numero_reloj) VALUES ($mark_code, $mark_date, $mark_minutes, $id)";
            echo '' . $query . '<br>';
            $resultPg = pg_query($connect, $query);
            $estadoPg = pg_connection_status($connect);
            if ($estadoPg === PGSQL_CONNECTION_OK) {
                echo 'Conexión OK';
            } else {
                echo 'Se perdió la conexión';
            }

            if (!$resultPg) {
                die('Error al ejecutar la consulta: ' . pg_last_error($connect));
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
