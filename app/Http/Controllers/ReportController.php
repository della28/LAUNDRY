<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use App\Detail_trans;
use App\Jenis;
use JWTAuth;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class ReportController extends Controller
{
  public function report($tgl_awal, $tgl_akhir){
          $transaksi = DB::table('transaksi')
          ->join('pelanggan', 'pelanggan.id', '=', 'transaksi.id_pelanggan')
          ->join('petugas', 'petugas.id', '=', 'transaksi.id_petugas')
          ->where('tgl_trans', '>=', $tgl_awal)
          ->where('tgl_trans', '<=', $tgl_akhir)
          ->select('transaksi.tgl_trans', 'pelanggan.nama_pelanggan', 'pelanggan.alamat', 'pelanggan.telp',
                  'transaksi.tgl_selesai', 'transaksi.id')
          ->get();

          $hasil = array();

          foreach ($transaksi as $t){
            $grand = DB::table('detail_trans')
            ->where('id_trans', '=', $t->id)
            ->groupBy('id_trans')
            ->select(DB::raw('sum(subtotal) as grand_total'))
            ->first();

            $detail = DB::table('detail_trans')
            ->join('jenis_cuci', 'jenis_cuci.id', '=', 'detail_trans.id_jenis')
            ->where('id_trans', '=', $t->id)
            ->select('detail_trans.id_trans', 'jenis_cuci.nama_jenis', 'qty', 'subtotal')
            ->get();

            $hasil2 = array();

            foreach ($detail as $d){
              $hasil2[] = array(
                'id transaksi' => $d->id_trans,
                'jenis cuci' => $d->nama_jenis,
                'qty' => $d->qty,
                'subtotal' => $d->subtotal
              );
            }

            $hasil[] = array(
              'tgl transaksi' => $t->tgl_trans,
              'nama' => $t->nama_pelanggan,
              'alamat' => $t->alamat,
              'telp' => $t->telp,
              'tgl selesai' => $t->tgl_selesai,
              'total transaksi' => $grand,
              'detail transaksi' => $hasil2,
            );
          }

          return response()->json(compact('hasil'));
        }
}
