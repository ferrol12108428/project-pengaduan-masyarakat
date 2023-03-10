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
        <a href="{{ route('data')}}"> <button class="login-btn" style="margin-left: 10px; margin-top: -15px;">Refresh</button> </a>
        <a href="{{ route('export-pdf')}}"><button class="login-btn" style="margin-left: 10px; margin-top: -15px;">Cetak PDF</button></a>
        <a href="{{ route('export.excel')}}"><button class="login-btn" style="margin-left: 10px; margin-top: -15px;">Cetak Excel</button></a>
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
                    <?php
                    // substr_replace : mengubah karakter string
                    // punya 3 argumen. argumen ke-1 : data yang mau dimasukin ke string
                    // argumen ke-2 : muali dr index mana ubahnya
                    // argumen ke-3 : sampai index mana diubahnya
                    $telp = substr_replace($data->no_telp, "62", 0, 1)
                    ?>
                    <?php
                    // kalau uda di response dat reportnya, cht wa nya data dr response di tampilkan 
                        if ($data->response) {
                            $pesanWa = 'Hallo ' . $data->nama . '! pengaduan anda di' . $data->response['status'] . ', Berhasil pesan untuk anda :' . $data->response['pesan'];
                        }
                        // kalau belum di response pengaduannya, cht wa nya kaya gini
                        else {
                            $pesanWa = 'Belum ada data response!';
                        }
                    ?>
                    <td><a href="https://wa.me/{{$telp}}?text={{$pesanWa}}" target="_blank">{{$telp}}</a></td>
                    <td>{{$data['pengaduan']}}</td>
                    <td>
                        <a href="../assets/img/{{$data->foto}}" target="_blank">
                            <img src="{{asset('assets/img/'.$data->foto)}}" width="120">
                        </a>
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
                    <td>
                        <form action="{{route('delete', $data->id)}}" method="post">
                            @csrf
                            <button class="btn-delete">Hapus</button>
                        </form>
                        <div>
                            <form action="{{route('create.pdf', $data->id)}}" method="get">
                                @csrf
                                <button class="btn-delete">Cetak PDF</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>