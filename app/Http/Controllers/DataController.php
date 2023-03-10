<?php

namespace App\Http\Controllers;

use App\Models\data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\DataExport;
use App\Models\Response;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function exportPDF() {
        //ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan gunakan pagination
        //jangan lupa konvert ke array dengan toArray()
        $datas = Data::with('response')->get()->toArray();
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial
        view()->share('datas',$datas);
        // panggil view balde yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print',$datas)->setPaper('a4', 'landscape');
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function createdPDF($id) {
        //ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan gunakan pagination
        //jangan lupa konvert ke array dengan toArray()
        $datas = Data::with('response')->where('id', $id)->get()->toArray();
        // kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial
        view()->share('datas', $datas);
        // panggil view balde yg akan dicetak pdf serta data yg akan digunakan
        $pdf = PDF::loadView('print', $datas)->setPaper('a4', 'landscape');
        // download PDF file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function exportExcel() {
        // nama file yang akan terdownload
        // selain .xlsx juga bisa .csv
        $file_name = 'data_keseluruhan_pengaduan.xlsx';
        // memanggil file DataExport dan mendonloadnya dengan nama seperti $file_name
        return Excel::download(new DataExport, $file_name);
    }

    public function dataPetugas(Request $request) {
        $search = $request->search;
        $datas = Data::with('response')->where('nama', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data_petugas', compact('datas'));
    }

     public function index()
    {
        // pake GET
        // $data = Data::get();

        // return view('index')
        // ->with('data', $data);

        // pake COMPACT
        //  ASC : ascending -> terkecil
        $datas = Data::orderBy('created_at', 'DESC')
        ->simplePaginate(2);

        return view('index', compact('datas'));
    }

    // Request $request ditambahkan karna pada halaman data ada fitur search nya, dan akan mengambil text yg diinput search
    public function data (Request $request) {
        // ambil data yg diinput ke input yg name nya search
        $search = $request->search;
        // where akan mencari data berdasarkan column nama
        // data yang diambil merupakan data yg 'LIKE' (terdapat) text yang dimasukin ke input search
        // contoh : ngisi input search dengan 'fema'
        // bakal nyari ke db yg column nama nya ada isi 'fema' nya
        $datas = Data::with('response')->where('nama', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'DESC')->get();
        return view('data', compact('datas'));
    }

    public function logout (Request $request) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('login')
        ->with('success', 'Berhasil logout');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        //ambil data dan simpan di variable
        $user = $request->only('email', 'password');
        //simen data ke auth dengan Auth::attempt
        //cek progres penyimpanan ke auth berhasil ato tidak lewat if else
        // nesting if, if bersarang if didalam if
        // kalau data login uda masuk ke fitur Auth, dicek lagi pake if-else
        // kalau data Auth tersebut role nya petugas maka masuk ke route data
        // kalau data Auth role nya petugas maka masuk ke route data.petugas
        if (Auth::attempt($user)) {
            if (Auth::user()->role == 'admin') {
                return redirect('/data');
            } elseif (Auth::user()->role == 'petugas') {
                return redirect()->route('data.petugas');
            }
        } else {
            return redirect()->back()->with('failed', 'Gagal login, coba lagi!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|max:13',
            'pengaduan' => 'required|min:5',
            'foto' => 'required|image|mimes:jpg,jpeg,png,svg',
        ]);

        $path = public_path('assets/img/');
        $image = $request->file('foto');
        $imgName = rand() . '.' . $image->extension();
        $image->move($path, $imgName);

        Data::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,
            'pengaduan' => $request->pengaduan,
            'foto' => $imgName,
        ]);
        return redirect()->route('index');
        with('success', 'Berhasil menambahkan pengaduan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function show(data $data)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function edit(data $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, data $data)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        // cari data yang dimaksud
        $datas = Data::where('id', $id)->firstOrFail();
        // $data isinya -> nik sampe foto dr pengaduan
        // hapus data foto dr folder public : path . nama foto nya
        // nama foto nya diambil dari $datas yg diatas trs ngambil dr column 'foto'
        $image = public_path('assets/img/'.$datas['foto']);
        // uda nemu posisi fotonya, tinggal di hapus fotonya pake unlink
        unlink($image);
        // hapus $data yg isinya data nik-foto tadi , hapusnya di database
        $datas->delete();
        // setelahnya dikembalikan lg ke halaman awal
        Response::where('data_id', $id)->delete();
        return redirect()->back();
    }
}
