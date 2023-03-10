<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Pengaduan</title>
</head>

<body>
    <h2 style="text-align: center; margin-bottom: 20px;">Data Keseluruhan Pengaduan</h2>
    <table style="width: 100%;">
        <tr>
            <th>NO</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Tanggal</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
        </tr>
        <?php $i = 1;  ?>
        @foreach ($datas as $data)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$data['nik']}}</td>
            <td>{{$data['nama']}}</td>
            <td>{{$data['no_telp']}}</td>
            <td>{{\Carbon\Carbon::parse($data['created_at'])->format('j F, Y')}}</td>
            <td>{{$data['pengaduan']}}</td>
            <td>
                <img src="assets/img/{{$data['foto']}}" width="80">
            </td>
            <td>
                <!-- cek apakah ada data report ini sudah memiliki realasi dengan data dr with ('response') -->
                @if ($data['response'])
                <!-- kalau ada hasil relasinya, tampilkan bagian status -->
                {{ $data['response']['status']}}
                @else
                <!-- kalau gada tampilkan tanda ini -->
                -
                @endif
            </td>
            <td>
                <!-- cek apakah ada data report ini sudah memiliki realasi dengan data dr with ('response') -->
                @if ($data['response'])
                <!-- kalau ada hasil relasinya, tampilkan bagian status -->
                {{ $data['response']['pesan']}}
                @else
                <!-- kalau gada tampilkan tanda ini -->
                -
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>