<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')
@php
    use Rats\Zkteco\Lib\ZKTeco;

    // Define las instancias de ZKTeco para cada dispositivo

    $zkDevices = [
        'zk' => ['device' => new ZKTeco('9.10.8.103', 4370), 'id' => 1],
        'zk1' => ['device' => new ZKTeco('9.10.8.102', 4370), 'id' => 2],
        'zk2' => ['device' => new ZKTeco('9.10.8.104', 4370), 'id' => 3],
        'zk3' => ['device' => new ZKTeco('9.10.8.105', 4370), 'id' => 4],
        'zk4' => ['device' => new ZKTeco('9.10.8.106', 4370), 'id' => 5],
        'zk5' => ['device' => new ZKTeco('9.10.8.101', 4370), 'id' => 6], //administrativos
        'zk6' => ['device' => new ZKTeco('9.10.8.107', 4370), 'id' => 7],
        'zk7' => ['device' => new ZKTeco('9.10.8.108', 4370), 'id' => 8],
        'zk8' => ['device' => new ZKTeco('9.10.8.109', 4370), 'id' => 9],
        'zk9' => ['device' => new ZKTeco('9.10.8.110', 4370), 'id' => 10],
        'zk10' => ['device' => new ZKTeco('9.10.8.111', 4370), 'id' => 11],
        // Agrega instancias para otros dispositivos
    ];

    // Otras configuraciones y conexiones
    foreach ($zkDevices as $deviceInfo) {
        $device = $deviceInfo['device'];
        if ($device->connect()) {
            // echo "".json_encode($usersAll);
            $device->disconnect();
        }
    }

    foreach ($relojes as $reloj) {
        $device2 = new ZKTeco($reloj->host, $reloj->port);
        if ($device2->connect()) {
            // echo "".$reloj->host;
            $device2->disconnect();
        }
    }

    
    // Obtiene los datos del dispositivo seleccionado
    $selectedDeviceKey = request()->input('selected_device', 'zk'); // Por defecto, selecciona 'zk'
    $selectedDeviceInfo = $zkDevices[$selectedDeviceKey];
    $selectedDevice = $selectedDeviceInfo['device'];

    if ($selectedDevice->connect()) {
        $serie = $selectedDevice->serialNumber();
        $selectedUsers = $selectedDevice->getAttendance();
        $selectedDevice->disconnect();
    } else {
        $selectedUsers = [];
    }
@endphp

@section('content')
    <div class="container mt-3 bg-light p-4 rounded shadow">
        <div class="header d-flex justify-content-between align-items-center bg-primary text-white p-3 rounded">
            <h1>Panel de control</h1>
            <a href="{{ route('login') }}" class="btn btn-light btn-logout">Cerrar sesión</a>
        </div>

        <div class="header d-flex justify-content-between align-items-center bg-primary text-white p-3 rounded mt-3">
            <a href="{{ route('indexUpMark') }}" class="btn btn-light">Actualizar registros</a>
        </div>

        <!-- Combo box para seleccionar dispositivo -->
        <div class="mt-3">
            <h2>Seleccionar Dispositivo:</h2>
            <form method="post" action="{{ route('seleccionar_dispositivo') }}" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <select name="selected_device" class="form-control">
                        @foreach ($zkDevices as $key => $device)
                            <option value="{{ $key }}" @if ($selectedDeviceKey === $key) selected @endif>
                                {{ $key }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Seleccionar</button>
            </form>
        </div>

        <!-- Mostrar información del dispositivo seleccionado -->
        <div class="mt-4">
            <h5>Información del Marcador</h5>
            <p>Dispositivo: {{ $serie }}</p>
            <!-- ... Otros detalles del dispositivo ... -->
        </div>

        <!-- Tabla de Asistencia del dispositivo seleccionado -->
        <div class="mt-4">
            <h4>Registros actuales en el reloj</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            {{-- <th>No.</th> --}}
                            <th>Key</th>
                            {{-- <th>Uid</th> --}}
                            <th>User</th>
                            <th>Date</th>
                            <th>hoursToMinutes</th>
                            <th>DateNumber</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 0; @endphp
                        @foreach ($selectedUsers as $key => $user)
                            @php $no++; @endphp
                            <tr>
                                {{-- <td align="right">{{ $no }}</td> --}}
                                {{-- <td align="right">{{ $key }}</td> --}}
                                <td align="right">{{ json_encode($user) }}</td>

                                {{-- <td>{{ $key }}</td>
                                <td>{{ $user['uid'] }}</td> --}}
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['timestamp'] }}</td>
                                <td>{{ hoursToMinutes($user['timestamp']) }}</td>
                                <td>{{ dateToNumber($user['timestamp']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #ecf0f1;
            /* Color de fondo general */
        }

        .header {
            background-color: #3498db;
            /* Color de fondo de las secciones de encabezado */
            color: #ffffff;
        }

        .btn-logout {
            margin-right: 10px;
        }

        tr:hover {
            background-color: #e0e0e0;
            /* Color de fondo al pasar el ratón sobre las filas de la tabla */
        }
    </style>
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
