<!DOCTYPE html>
<html>
<head>
    <title>Sensor Data</title>
</head>
<body>
    <h1>Data Sensor</h1>

    @foreach ($sensor_data as $data)
            <li>{{ $data->sensor_id }} - Temp: {{ $data->temperature }}°C, Humidity: {{ $data->humidity }}%, Timestamp: {{ $data->timestamp }}</li>
        @endforeach
    </ul>
</body>
</html>
