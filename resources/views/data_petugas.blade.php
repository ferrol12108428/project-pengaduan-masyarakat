<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>

<body>
    <h2 class="title-table">Laporan Keluhan</h2>
    <div style="display: flex; justify-content: center; margin-bottom: 30px">
        <a class="login-btn" href="{{route('logout')}}" style="text-align: center">Logout</a>
        <div style="margin: 0 10px"></div>
        <a class="login-btn" href="{{route('index')}}" style="text-align: center">Home</a>
    </div>
    <div style="display: flex; justify-content: flex-end; align-items: center;">
        <!-- menggunakan method GET karna route akan masuk ke halaman data ini menggunakan ::get -->
        <form action="" method="GET">
            @csrf
            <input type="text" name="search" placeholder="Cari Berdasarkan nama....">
            <button type="submit" class="btn-login" style="margin-top: -1px;">Cari</button>
        </form>
        <!-- Refresh bailk lg ke ke route data karna nanti pas di klik Refresh 
        bersihin riwayat pencarian sebelumnya dan balikin lagi nya ke halaman data lagi -->
        <a href="{{ route('data')}}"> <button class="login-btn" style="margin-left: 10px; margin-top: -15px;">Refresh</button></a>
    </div>
    <div style=" padding: 0 30px">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Telp</th>
                    <th>Pengaduan</th>
                    <th>Gambar</th>
                    <th>Status Response</th>
                    <th>Pesan Response</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($datas as $data)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$data['nik']}}</td>
                    <td>{{$data['nama']}}</td>
                    <td>{{$data['no_telp']}}</td>
                    <td>{{$data['pengaduan']}}</td>
                    <td>
                        <img src="{{asset('assets/img/'.$data->foto)}}" width="120">
                    </td>
                    <td>
                        <!-- cek apakah ada data report ini sudah memiliki realasi dengan data dr with ('response') -->
                        @if ($data->response)
                        <!-- kalau ada hasil relasinya, tampilkan bagian status -->
                        {{ $data->response['status']}}
                        @else
                        <!-- kalau gada tampilkan tanda ini -->
                        -
                        @endif
                    </td>
                    <td>
                        <!-- cek apakah ada data report ini sudah memiliki realasi dengan data dr with ('response') -->
                        @if ($data->response)
                        <!-- kalau ada hasil relasinya, tampilkan bagian status -->
                        {{ $data->response['pesan']}}
                        @else
                        <!-- kalau gada tampilkan tanda ini -->
                        -
                        @endif
                    </td>
                    <td style="display: flex; justify-content: center;">
                        <a href="{{route('response.edit', $data->id)}}" class="back-btn">Send Response</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>