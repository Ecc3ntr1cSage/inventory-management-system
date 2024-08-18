<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $stock }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        table th,
        table td {
            padding: 8px;
            border: 1px solid #000;
            letter-spacing: 0.05em;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>{{ $stock }}</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">
                    Tarikh
                </th>
                <th rowspan="2">
                    No rujukan BTB/BPPS
                </th>
                <th colspan="3">
                    Kuantiti
                </th>
                <th rowspan="2">
                    Nama
                </th>
            </tr>
            <tr>
                <th>
                    Terima
                </th>
                <th>
                    Keluar
                </th>
                <th>
                    Baki
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($indexes as $index)
            <tr>
                <td>
                    {{ $index->date }}
                </td>
                <td>
                    {{ $index->reference_no }}
                </td>
                <td>
                    {{ $index->in_quantity }}
                </td>
                <td>
                    {{ $index->out_quantity }}
                </td>
                <td>
                    {{ $index->balance }}
                </td>
                <td>
                    {{ $index->name }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
