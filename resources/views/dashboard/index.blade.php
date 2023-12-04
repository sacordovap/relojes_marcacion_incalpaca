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
    $zk1 = new ZKTeco('9.10.8.102', 4370);
    $zk = new ZKTeco('9.10.8.103', 4370);
    $zk2 = new ZKTeco('9.10.8.104', 4370);
    $zk3 = new ZKTeco('9.10.8.105', 4370);
    $zk4 = new ZKTeco('9.10.8.106', 4370);
    $zk5 = new ZKTeco('9.10.8.101', 4370);
    $zk6 = new ZKTeco('9.10.8.107', 4370);
    $zk7 = new ZKTeco('9.10.8.108', 4370);
    $zk8 = new ZKTeco('9.10.8.109', 4370);
    $zk9 = new ZKTeco('9.10.8.110', 4370);
    $zk10 = new ZKTeco('9.10.8.111', 4370);

    // Intentar conectar al dispositivo
    if ($zk->connect()) {
        // Obtener información del dispositivo
        $serie = $zk->serialNumber();
        $users = $zk->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk->disconnect();
    } else {
        // Mostrar mensaje de error si no se pudo conectar
        $users = [];
    }
    if ($zk1->connect()) {
        // Obtener información del dispositivo
        $serie1 = $zk1->serialNumber();
        $users1 = $zk1->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk1->disconnect();
    }
    if ($zk2->connect()) {
        // Obtener información del dispositivo
        $serie2 = $zk2->serialNumber();
        $users2 = $zk2->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk2->disconnect();
    }
    if ($zk3->connect()) {
        // Obtener información del dispositivo
        $serie3 = $zk3->serialNumber();
        $users3 = $zk3->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk3->disconnect();
    }
    if ($zk4->connect()) {
        // Obtener información del dispositivo
        $serie4 = $zk4->serialNumber();
        $users4 = $zk4->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk4->disconnect();
    }
    if ($zk5->connect()) {
        // Obtener información del dispositivo
        $serie5 = $zk5->serialNumber();
        $users5 = $zk5->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk5->disconnect();
    }
    if ($zk6->connect()) {
        // Obtener información del dispositivo
        $serie6 = $zk6->serialNumber();
        $users6 = $zk6->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk6->disconnect();
    }
    if ($zk7->connect()) {
        // Obtener información del dispositivo
        $serie7 = $zk7->serialNumber();
        $users7 = $zk7->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk7->disconnect();
    }
    if ($zk8->connect()) {
        // Obtener información del dispositivo
        $serie8 = $zk8->serialNumber();
        $users8 = $zk8->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk8->disconnect();
    }
    if ($zk9->connect()) {
        // Obtener información del dispositivo
        $serie9 = $zk9->serialNumber();
        $users9 = $zk9->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk9->disconnect();
    }
    if ($zk10->connect()) {
        // Obtener información del dispositivo
        $serie10 = $zk10->serialNumber();
        $users10 = $zk10->getAttendance(); // Update this line
        // Desconectar del dispositivo
        $zk10->disconnect();
    }
@endphp

@section('content')
    <h1>Dashboard</h1>
    <div>
        <h2>Información del Dispositivo ZKTeco</h2>}
        <p>Dispositivo: {{ $serie }}</p>
        <p>Dispositivo: {{ $serie1 }}</p>
        <p>Dispositivo: {{ $serie2 }}</p>
        <p>Dispositivo: {{ $serie3 }}</p>
        <p>Dispositivo: {{ $serie4 }}</p>
        <p>Dispositivo ADMINISTRATIVOS: {{ $serie5 }}</p>
        <p>Dispositivo: {{ $serie6 }}</p>
        <p>Dispositivo: {{ $serie7 }}</p>
        <p>Dispositivo: {{ $serie8 }}</p>
        <p>Dispositivo: {{ $serie9 }}</p>
        <p>Dispositivo: {{ $serie10 }}</p>
        {{-- <p>Dispositivo: {{ $deviceInfo['Product'] }}</p>
        <p>Modelo: {{ $deviceInfo['Model'] }}</p>
        <p>Conectado a: {{ $zkConfig['ip'] }}:{{ $zkConfig['port'] }}</p> --}}

        <a href="{{ route('login') }}" class="btn btn-primary">Regresar al Login</a>
    </div>

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

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Key</th>
                <th>Uid</th>
                <th>User</th>
                <th>Date</th>
                <th>hoursToMinutes</th>
                <th>DateNumber</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach ($users5 as $key => $user)
                @php $no++; @endphp
                <tr>
                    <td align="right">{{ $no }}</td>
                    <td>{{ $key }}</td>
                    <td>{{ $user['uid'] }}</td>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['timestamp'] }}</td>
                    <td>{{ hoursToMinutes($user['timestamp']) }}</td>
                    <td>{{ dateToNumber($user['timestamp']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <form>
        <!-- Resto del formulario -->
        <button type="submit">Enviar</button>
    </form>
@endsection
@php
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
@endphp
