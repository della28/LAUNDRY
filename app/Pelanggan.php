<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
  protected $table="pelanggan";
  protected $primaryKey="id";
  protected $fillable = [
    'nama_pelanggan',
    'telp',
    'alamat'
  ];

  public function pelanggan(){
    return $this->hasMany('App\Pelanggan','id');
  }
}
