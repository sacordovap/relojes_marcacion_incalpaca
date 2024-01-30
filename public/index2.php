<?php

// Nombre de usuario, contraseña y nombre de la base de datos
$user = 'icptpx';
$password = '';
$database = 'sigo';

// Parámetros de conexión
$connectionString = "host=9.10.70.105 port=5432 dbname=$database user=$user password=$password";

// Conexión a PostgreSQL
$conn = pg_connect($connectionString);

if (!$conn) {
    die('Error de conexión: ' . pg_last_error());
}

// Nombre de la vista en PostgreSQL
$nombreVista = 'perbol';
$percod = '02147';
// Consulta a la vista
$condicionWhere = "percod = '$percod'";
$query = "SELECT * FROM $nombreVista WHERE $condicionWhere";
$result = pg_query($conn, $query);

if (!$result) {
    die('Error en la consulta: ' . pg_last_error());
}

if ($result) {
    // Obtener resultados y mostrarlos
    while ($row = pg_fetch_assoc($result)) {
        echo "ID: " . $row['percod'] . ", Nombre: ". $row['nomcom'] .  "<br>";
        // Puedes personalizar este formato según la estructura de tu tabla
    }
} else {
    // Manejar errores si la consulta no fue exitosa
    echo "Error en la consulta: " . pg_last_error($conexion);
}

// Cerrar la conexión
pg_close($conn);
