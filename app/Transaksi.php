<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
  protected $table="transaksi";
  protected $primaryKey="id";
  protected $fillable = [
    'id_pelanggan',
    'id_petugas',
    'tgl_trans',
    'tgl_selesai'
  ];

  public function pelanggan() {
    return $this->belongsTo('App/Pelanggan', 'id_pelanggan');
  }
  public function petugas() {
    return $this->belongsTo('App/Petugas', 'id_petugas');
  }
  public function detail(){
    return $this->hasMany('App\Detail_trans','id');
  }

}
