<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')
@php
    use Rats\Zkteco\Lib\ZKTeco;

    // Define las instancias de ZKTeco para cada dispositivo

    foreach ($relojes as $reloj) {
        $device = new ZKTeco($reloj->host, $reloj->port);
        $idRelog = $reloj->id;
        $area = $reloj->area;
        $numReloj = $reloj->numreloj;

        // Conecta el dispositivo y obtiene los datos necesarios
        if ($device->connect()) {
            // $serie = $device->serialNumber();
            // $selectedUsers = $device->getAttendance();

            // Agrega el dispositivo al array usando $idRelog como clave
            if ($idRelog > 1) {
                $zkDevices['zk' . $idRelog - 1] = ['device' => $device, 'area' => $area, 'numReloj' => $numReloj];
            } else {
                $zkDevices['zk'] = ['device' => $device, 'area' => $area, 'numReloj' => $numReloj];
            }
            $device->disconnect();
        } else {
            $selectedUsers = [];
        }
    }

    // Obtiene los datos del dispositivo seleccionado, me muestra ZK o ZK1 etc...
    $selectedDeviceKey = request()->input('selected_device', 'zk'); // Por defecto, selecciona 'zk'

    $selectedDeviceInfo = $zkDevices[$selectedDeviceKey];
    //OBTENIENDO DATOS DEL REJOS SELECCIONADO
    $areaSelected = $selectedDeviceInfo['area'];
    $numRelojSelected = $selectedDeviceInfo['numReloj'];

    //CONEXION DEL RELOJ
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
            <h3>Seleccionar Dispositivo:</h3>
            <form method="post" action="{{ route('seleccionar_dispositivo') }}" class="form-inline">
                @csrf
                <div class="form-group mr-2">
                    <select name="selected_device" class="form-control">
                        @foreach ($zkDevices as $key => $deviceInfo)
                            <option value="{{ $key }}" @if ($selectedDeviceKey === $key) selected @endif>
                                {{ $key . (isset($deviceInfo['area']) ? ' - ' . $deviceInfo['area'] : '') }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <button type="submit" class="btn btn-primary">Seleccionar</button> --}}
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            $(document).ready(function() {
                // Escucha el evento de cambio en el select
                $('select[name="selected_device"]').on('change', function() {
                    // Envía automáticamente el formulario cuando cambia la selección
                    $(this).closest('form').submit();
                });
            });
        </script>

        <!-- Mostrar información del dispositivo seleccionado -->
        <div class="mt-4">
            <h5>Información del Marcador</h5>
            <p>Dispositivo: {{ $serie }}</p>
            <p>Ubicación: {{ $areaSelected }}</p>
            <p>Número de Reloj: {{ $numRelojSelected }}</p>
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
                            <th align="center">Index</th>
                            {{-- <th>Uid</th> --}}
                            <th align="center">Usuario Cod</th>
                            <th align="center">Fecha y hora de marcado</th>
                            <th align="center">Hora en Minutos</th>
                            <th align="center">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 0; @endphp
                        @foreach ($selectedUsers as $key => $user)
                            @php $no++; @endphp
                            <tr>
                                <td align="center">{{ $no }}</td>
                                {{-- <td align="right">{{ $key }}</td> --}}
                                {{-- <td align="right">{{ json_encode($user) }}</td> --}}

                                {{-- <td>{{ $key }}</td> --}}
                                {{-- <td>{{ $user['uid'] }}</td> --}}
                                <td align="center">{{ $user['id'] }}</td>
                                <td align="center">{{ $user['timestamp'] }}</td>
                                <td align="center"> {{ hoursToMinutes($user['timestamp']) }}</td>
                                <td align="center">{{ dateToNumber($user['timestamp']) }}</td>
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
