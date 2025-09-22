<!DOCTYPE html>
<html>
<head>
    <title>Employee Data</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px; }
        thead tr { background-color: #f2f2f2; }
        .no { width: 4%; }
    </style>
</head>
<body>
    <h2>Data Karyawan</h2>
    <table>
        <thead>
            <tr>
                <th class="no">No</th>
                <th>Name</th>
                <th>Posisi</th>
                <th>TTL</th>
                <th>No. NIK</th>
                <th>No Induk Karyawan</th>
                <th>Mitra</th>
                <th>Jenis BPJS</th>
                <th>NO BPJS Kesehatan</th>
                <th>NO BPJS Ketenaga Kerjaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employes->sortBy('name') as $index => $employe)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employe->name }}</td>
                    <td>
                        @php
                            $user = \App\Models\UserAbsensi::whereRaw('LOWER(nama_lengkap) = ?', [strtolower($employe->name ?? '')])
                                ->with('jabatan')
                                ->first();

                            $posisi = $user
                                ? $user->jabatan->name_jabatan
                                : 'Data NotFound In Absensi';
                        @endphp

                        <span class="badge bg-{{ $posisi === 'Data NotFound In Absensi' ? 'danger' : 'secondary' }}">
                            {{ $posisi }}
                        </span>
                    </td>
                    <td>{{ $employe->ttl ?? '-' }}</td>
                    <td>{{ $employe->no_ktp }}</td>
                    <td> 
                    @php
                        $initials = $employe->initials ?? '';
                        $numbers = $employe->numbers ?? '';
                        $dateReal = $employe->date_real;

                        $formattedDate = $dateReal ? date('m-Y', strtotime($dateReal)) : '';

                        $result = trim("{$initials} {$numbers} {$formattedDate}");
                    @endphp

                    {{ $result }}</td>
                    <td>{{ $employe->client->name ?? '-' }}</td>
                    <td>
                        {{ implode(', ', $employe->jenis_bpjs ?? []) ?: '-' }}
                    </td>
                    <td>{{ $employe->no_bpjs_kesehatan ?? '-' }}</td>
                    <td>{{ $employe->no_bpjs_ketenaga ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>