<!-- resources/views/sqlserver/index.blade.php -->

<h1>Top 10 Marcaciones SQL Server</h1>

<table>
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
        @foreach($marcaciones as $marcacion)
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
