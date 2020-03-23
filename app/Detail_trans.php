<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_trans extends Model
{
  protected $table="detail_trans";
  protected $primaryKey="id";
  protected $fillable = [
    'id_trans',
    'id_jenis',
    'subtotal',
    'qty'
  ];

  public function jenis() {
    return $this->belongsTo('App/Jenis', 'id_jenis');
  }
  public function transaksi() {
    return $this->belongsTo('App/Transaksi', 'id_trans');
  }

}
