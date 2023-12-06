@php
    function insertarMarcaciones($selectedUsers, $zkDevices, $selectedDeviceKey)
    {
        $serverName = 'TPXSVBD02\CONTSQLDB,61865';
        $connectionInfo = ['Database' => env('DB_DATABASE'), 'UID' => env('DB_USERNAME'), 'PWD' => env('DB_PASSWORD')];
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if (!$conn) {
            die(json_encode(['error' => 'Error de conexión a la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
        }

        foreach ($selectedUsers as $user) {
            $mark_code = $user['uid'];
            $mark_date = dateToNumber($user['timestamp']);
            $mark_minutes = hoursToMinutes($user['timestamp']);

            $query = "INSERT INTO marcaciones (cod_trabajador, fecha_marcacion, hora_marcacion, numero_reloj) 
              VALUES ($mark_code, $mark_date, $mark_minutes, 3)";

            $result = sqlsrv_query($conn, $query);

            if (!$result) {
                sqlsrv_close($conn);
                die(json_encode(['error' => 'Error al insertar marcaciones en la base de datos', 'details' => sqlsrv_errors()], JSON_PRETTY_PRINT));
            }
        }

        sqlsrv_close($conn);
        echo json_encode(['message' => 'Inserción exitosa en la base de datos'], JSON_PRETTY_PRINT);
    }

    // Obtén los datos de la solicitud AJAX
    $data = json_decode(file_get_contents('php://input'), true);

    // Ejecuta la función con los datos de la solicitud
    insertarMarcaciones($data['selectedUsers'], $data['zkDevices'], $data['selectedDeviceKey']);

@endphp
