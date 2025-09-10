<!DOCTYPE html>
<html>
<head>
    <title>Kontrak Aktif</title>
</head>
<body>
    <h2>Kontrak Masih Aktif</h2>
    <p>Nama: {{ $user->nama_lengkap }}</p>
    <p>Kontrak masih berlaku hingga {{ $user->tgl_selesai_kontrak ?? 'Tanggal tidak tersedia' }}.</p>
</body>
</html>
