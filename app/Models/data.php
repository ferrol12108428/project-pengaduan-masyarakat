<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Response;

class data extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik',
        'nama',
        'no_telp',
        'pengaduan',
        'foto',
    ];
    // Hasone : one to one
    // table yg berperan sebagai PK
    // nama fungsi == nama model FK
    public function response()
    {
        return $this->hasOne(Response::class);
    }
}
