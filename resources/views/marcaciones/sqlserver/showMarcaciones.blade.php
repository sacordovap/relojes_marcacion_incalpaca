<!-- resources/views/sqlserver/index.blade.php -->
@extends('layouts.app')

@section('content')
<h1>Top 10 Marcaciones SQL Server</h1>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #9c9b53c7;
    }

    td {
        white-space: nowrap;
    }
</style>
<table >
    <thead>
        <tr>
            <th>Cod Trabajador</th>
            <th>Fecha Marcación</th>
            <th>Hora Marcación</th>
            <th>Número Reloj</th>
            <th>Número Tarjeta</th>
            <th>DNI</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($marcaciones as $marcacion)
            <tr>
                <td>{{ $marcacion->cod_trabajador }}</td>
                <td>{{ $marcacion->fecha_marcacion }}</td>
                <td>{{ $marcacion->hora_marcacion }}</td>
                <td>{{ $marcacion->numero_reloj }}</td>
                <td>{{ $marcacion->numero_tarjeta }}</td>
                <td>{{ $marcacion->dni }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

