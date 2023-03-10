<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\data;

class Response extends Model
{
    use HasFactory;
    protected $fillable = [
        'data_id',
        'status',
        'pesan',
    ];
    // belongsTo : disambungkan dengan table mana (PK nya ada dimana)
    // table yang berperan sebagai FK
    // nama function == nama model PK
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
