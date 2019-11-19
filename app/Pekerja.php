<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pekerja extends Model
{
    //table name
    protected $table = 'pekerja';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'email',
        'password',
        'status'
    ];

    public function listPekerja()
    {
        return $this->hasOne('App\DetailPekerja', 'id_pekerja', 'id');
    }
}
