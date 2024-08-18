<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $asset->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 10px;
        }

        h3 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .table-top {
            margin-bottom: 1rem;
        }

        .table-bottom {
            text-align: center;
            page-break-after: always;
        }

        .table-bottom p {
            text-align: left;
        }

        table th,
        table td {
            padding: 6px;
            border: 1px solid #000;
            letter-spacing: 0.05em;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @foreach($applications as $application)
    <h3>BORANG PERMOHONONAN PERGERAKAN/PINJAMAN ASET ALIH</h3>
    <table class="table-top">
        <tbody>
            <tr>
                <td>
                    Nama Pemohon:
                </td>
                <td>
                    {{ $application->user->name }}
                </td>
                <td>
                    Tujuan:
                </td>
                <td>
                    {{ $application->reason }}
                </td>
            </tr>
            <tr>
                <td>
                    Jawatan:
                </td>
                <td>
                    {{ $application->position }}
                </td>
                <td>
                    Tempat Digunakan:
                </td>
                <td>
                    {{ $application->location }}
                </td>
            </tr>
            <tr>
                <td>
                    Bahagian:
                </td>
                <td>
                    {{ $application->department }}
                </td>
                <td>
                    Nama Pengeluar:
                </td>
                <td>
                    {{ $application->handler }}
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <table class="table-bottom">
        <thead>
            <tr>
                <th rowspan="2">
                    Bil
                </th>
                <th rowspan="2">
                    No. Siri Pendaftaran
                </th>
                <th rowspan="2">
                    Keterangan Aset
                </th>
                <th colspan="2">
                    Tarikh
                </th>
                <th rowspan="2">
                    (Lulus/Tidak Lulus)
                </th>
                <th colspan="2">
                    Tarikh
                </th>
                <th rowspan="2">
                    Catatan
                </th>
            </tr>
            <tr>
                <th>
                    Dipinjam
                </th>
                <th>
                    Dijangka Pulang
                </th>
                <th>
                    Dipulangkan
                </th>
                <th>
                    Diterima
                </th>
            </tr>
        </thead>
        <tbody class="bg-neutral-150">
            <tr>
                <td>

                </td>
                <td>
                    {{ $asset->registration_no }}
                </td>
                <td class="nowrap">
                    {{ $asset->name }} <br /> {{ $asset->model }}
                </td>
                <td class="nowrap">
                    {{ $application->date_issued }}
                </td>
                <td>

                </td>
                <td>
                    Lulus
                </td>
                <td class="nowrap">
                    {{ $application->date_returned }}
                </td>
                <td>

                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <br />
                    <p>......................................</p>
                    <p>(Tandangan Peminjam)</p>
                    <p>Nama: {{ $application->user->name }}</p>
                    <p>Jawatan: {{ $application->position }}</p>
                    <p>Tarikh: {{ $application->date_issued }}</p>
                </td>
                <td colspan="5">
                    <br />
                    <p>......................................</p>
                    <p>(Tandangan Pelulus)</p>
                    <p>Nama: {{ $application->handler }}</p>
                    <p>Jawatan:</p>
                    <p>Tarikh: {{ $application->date_issued }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <br />
                    <p>......................................</p>
                    <p>(Tandangan Pemulang)</p>
                    <p>Nama: {{ $application->user->name }}</p>
                    <p>Jawatan: {{ $application->position }}</p>
                    <p>Tarikh: {{ $application->date_returned }}</p>
                </td>
                <td colspan="5">
                    <br />
                    <p>......................................</p>
                    <p>(Tandangan Penerima)</p>
                    <p>Nama: {{ $application->receiver }}</p>
                    <p>Jawatan:</p>
                    <p>Tarikh: {{ $application->date_returned }}</p>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach

</body>

</html>
