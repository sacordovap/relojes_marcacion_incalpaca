<!-- resources/views/showMarcaciones.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Primeros 10 Registros de la Tabla Marcaciones PG</h1>

    <table class="table">
        <thead>
            <tr>
                <th>cod_trabajador</th>
                <th>fecha_marcacion</th>
                <th>hora_marcacion </th>
                <th>Reloj</th>

                <!-- Agrega más columnas según tu estructura de la tabla -->
            </tr>
        </thead>
        <tbody>
            @foreach ($marcaciones as $marcacion)
                <tr>
                    <td>{{ $marcacion->cod_trabajador }}</td>
                    <td>{{ $marcacion->fecha_marcacion }}</td>
                    <td>{{ $marcacion->hora_marcacion }}</td>
                    <td>{{ $marcacion->numero_reloj }}</td>
                    <!-- Agrega más celdas según tu estructura de la tabla -->
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
