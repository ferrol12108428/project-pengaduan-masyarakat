<?php

namespace App\Exports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Data::with('response')->orderBy('created_at', 'DESC')->get();
    }

    public function headings(): array {
        return [
            'ID',
            'NIK Pelapor',
            'Nama Pelapor',
            'No Telp Pelapor',
            'Tanggal Pelaporan',
            'Pengaduan',
            'Status Response',
            'Pesan Renponse',
        ];
    }

    public function map($item): array {
        return [
            $item->id,
            $item->nik,
            $item->nama,
            $item->no_telp,
            \Carbon\Carbon::parse($item->created_at)->format('j F, Y'),
            $item->pengaduan,
            $item->response ? $item->response['status'] : '-',
            $item->response ? $item->response['pesan'] : '-',
        ];
    }
}
