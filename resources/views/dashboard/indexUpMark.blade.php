<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@php
    use Rats\Zkteco\Lib\ZKTeco;

    // Configuración de conexión con el dispositivo ZKTeco
    // $zkConfig = [
    //     'ip' => '9.10.8.103',
    //     'port' => 4370,
    // ];

    // Crear una instancia de Rats\Zkteco\Zkteco
    // $zk = new ZKTeco($zkConfig['ip'], $zkConfig['port']);
    $zk1 = new ZKTeco('9.10.8.102', 4370); // 1
    $zk = new ZKTeco('9.10.8.103', 4370); //3
    $zk2 = new ZKTeco('9.10.8.104', 4370); //6
    $zk3 = new ZKTeco('9.10.8.105', 4370); //7
    $zk4 = new ZKTeco('9.10.8.106', 4370); //5
    $zk5 = new ZKTeco('9.10.8.101', 4370); //11
    $zk6 = new ZKTeco('9.10.8.107', 4370); //21
    $zk7 = new ZKTeco('9.10.8.108', 4370); //22
    $zk8 = new ZKTeco('9.10.8.109', 4370); //23
    $zk9 = new ZKTeco('9.10.8.110', 4370); //24
    $zk10 = new ZKTeco('9.10.8.111', 4370); //25

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

    // Intentar conectar al dispositivo
    // if ($zk->connect()) {
    //       // $zk->disableDevice();

    //     // $zk->enableDevice();
    //     // $zk->clearAttendance();
    //     // $zk->disconnect();
    //     // Obtener información del dispositivo
    //     $serie = $zk->serialNumber();
    //     $users = $zk->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users, $conn, 3);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk->disconnect();
    // } else {
    //     // Mostrar mensaje de error si no se pudo conectar
    //     $users = [];
    // }
    // if ($zk1->connect()) {
    //       // $zk1->disableDevice();

    //     // $zk1->enableDevice();
    //     // $zk1->clearAttendance();
    //     // $zk1->disconnect();
    //     // Obtener información del dispositivo
    //     $serie1 = $zk1->serialNumber();
    //     $users1 = $zk1->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users1, $conn, 1);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk1->disconnect();
    // }
    // if ($zk2->connect()) {
    //       // $zk2->disableDevice();

    //     // $zk2->enableDevice();
    //     // $zk2->clearAttendance();
    //     // $zk2->disconnect();
    //     // Obtener información del dispositivo
    //     $serie2 = $zk2->serialNumber();
    //     $users2 = $zk2->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users2, $conn, 6);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk2->disconnect();
    // }
    // if ($zk3->connect()) {
    //     // $zk->disableDevice();

    //     // $zk3->enableDevice();
    //     // $zk3->clearAttendance();
    //     // $zk3->disconnect();

    //     // Obtener información del dispositivo
    //     $serie3 = $zk3->serialNumber();
    //     $users3 = $zk3->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users7, $conn, 7);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);

    //     // Desconectar del dispositivo
    //     $zk3->disconnect();
    // }
    if ($zk4->connect()) {
        // $zk4->disableDevice();

        // $zk4->enableDevice();
        // $zk4->clearAttendance();
        // $zk4->disconnect();
        // Obtener información del dispositivo
        $serie4 = $zk4->serialNumber();
        $users4 = $zk4->getAttendance(); // Update this line
        if (!$connect) {
            $resultados[] = ['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()];
            die(json_encode(['error' => 'Error de conexión a la base de datos Postgres', 'details' => pg_last_error()], JSON_PRETTY_PRINT));
        } else {
            insertarData($users4, $conn, 5);
            $resultados[] = ['message' => 'Inserción exitosa en la base de datos'];
        }
        if (!$conn) {
            die(json_encode(['error' => 'Error de conexión a la base de datos SQLSERVER', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
        } else {
            // insertarDataPG($users4, $connect, 5);
        }

        // Desconectar del dispositivo
        $zk4->disconnect();
    } else {
        $resultados[] = ['error' => 'No se pudo conectar al dispositivo'];
    }
    // if ($zk5->connect()) {
    //     // $zk5->disableDevice();

    //     // $zk5->enableDevice();
    //     // $zk5->clearAttendance();
    //     // $zk5->disconnect();
    //     // Obtener información del dispositivo
    //     $serie5 = $zk5->serialNumber();
    //     $users5 = $zk5->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users5, $conn, 11);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk5->disconnect();
    // }
    // if ($zk6->connect()) {
    //     // $zk6->disableDevice();

    //     // $zk6->enableDevice();
    //     // $zk6->clearAttendance();
    //     // $zk6->disconnect();

    //     // Obtener información del dispositivo
    //     $serie6 = $zk6->serialNumber();
    //     $users6 = $zk6->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users6, $conn, 21);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk6->disconnect();
    // }
    // if ($zk7->connect()) {
    //     // $zk7->disableDevice();

    //     // $zk7->enableDevice();
    //     // $zk7->clearAttendance();
    //     // $zk7->disconnect();

    //     // Obtener información del dispositivo
    //     $serie7 = $zk7->serialNumber();
    //     $users7 = $zk7->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users7, $conn, 22);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk7->disconnect();
    // }
    // if ($zk8->connect()) {

    //     // $zk8->disableDevice();

    //     // $zk8->enableDevice();
    //     // $zk8->clearAttendance();
    //     // $zk8->disconnect();

    //     // Obtener información del dispositivo
    //     $serie8 = $zk8->serialNumber();
    //     $users8 = $zk8->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users8, $conn, 5);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk8->disconnect();
    // }
    // if ($zk9->connect()) {
    //     // $zk9->disableDevice();

    //     // $zk9->enableDevice();
    //     // $zk9->clearAttendance();
    //     // $zk9->disconnect();

    //     // Obtener información del dispositivo
    //     $serie9 = $zk9->serialNumber();
    //     $users9 = $zk9->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users9, $conn, 24);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk9->disconnect();
    // }
    // if ($zk10->connect()) {
    //     // $zk10->disableDevice();

    //     // $zk10->enableDevice();
    //     // $zk10->clearAttendance();
    //     // $zk10->disconnect();

    //     // Obtener información del dispositivo
    //     $serie10 = $zk10->serialNumber();
    //     $users10 = $zk10->getAttendance(); // Update this line

    //     if (!$conn) {
    //         die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
    //     }

    //     insertarData($users10, $conn, 25);

    //     echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    //     // Desconectar del dispositivo
    //     $zk10->disconnect();
    // }

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

    function insertarData($users, $conn, $id)
    {
        foreach ($users as $user) {
            $mark_code = $user['id'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);
            //INSERCION PARA SQLSERVER
            $query = "INSERT INTO marcaciones (cod_trabajador, fecha_marcacion, hora_marcacion, numero_reloj,numero_tarjeta,dni) 
              VALUES ($mark_code, $mark_date, $mark_minutes, $id,'0000000000','00000000')";

            $result = sqlsrv_query($conn, $query);

            if (!$result) {
                // sqlsrv_close($conn);
                die(json_encode(['error' => 'Error al insertar marcaciones en la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
            }
        }
    }
    function insertarDataPG($users, $connect, $id)
    {
        foreach ($users as $user) {
            $mark_code = $user['id'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);
            //INSERCION PARA POSTGRES
            $query = "INSERT INTO marcaciones (cod_trabajador,fecha_marcacion , hora_marcacion, numero_reloj) VALUES ($mark_code, $mark_date, $mark_minutes, $id)";

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
        @if ($success))
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