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

class TransaksiController extends Controller
{
    public function report($tgl_awal, $tgl_akhir){
      if(Auth::user()->level=="petugas"){
        $transaksi=DB::table('transaksi')
        ->join('pelanggan', 'pelanggan.id', '=', 'transaksi.id_pelanggan')
        ->join('petugas', 'petugas.id', '=', 'transaksi.id_petugas')
        ->where('transaksi.tgl_trans', '>=', $tgl_awal)
        ->where('transaksi.tgl_trans', '<=', $tgl_akhir)
        ->select('transaksi.id', 'tgl_trans', 'nama_pelanggan', 'alamat', 'pelanggan.telp', 'tgl_selesai')
        ->get();

        $datatrans=array(); $no=0;
        foreach ($transaksi as $tr) {
          $datatrans[$no]['id transaksi'] = $tr->id;
          $datatrans[$no]['tgl_trans'] = $tr->tgl_trans;
          $datatrans[$no]['nama_pelanggan'] = $tr->nama_pelanggan;
          $datatrans[$no]['alamat'] = $tr->alamat;
          $datatrans[$no]['telepon'] = $tr->telp;
          $datatrans[$no]['tgl_selesai'] = $tr->tgl_selesai;

          $grand=DB::table('detail_trans')->where('id_trans', $tr->id)->groupBy('id_trans')
          ->select(DB::raw('sum(subtotal) as grand_total'))->first();

          $datatrans[$no]['grand_total'] = $grand->grand_total;
          $detail=DB::table('detail_trans')->join('jenis_cuci','jenis_cuci.id', '=', 'detail_trans.id_jenis')
          ->where('id_trans', $tr->id)->select('jenis_cuci.nama_jenis', 'jenis_cuci.harga_kiloan', 'detail_trans.qty', 'detail_trans.subtotal')->get();

          $datatrans[$no]['detail'] = $detail;
          $no++;
          }
        return response()->json(compact("datatrans"));
      } else{
        return response()->json(['status'=>'anda bukan petugas']);
      }
        }




    public function store(Request $request){
      if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
          'id_pelanggan'=>'required',
          'id_petugas'=>'required',
          'tgl_trans'=>'required',
          'tgl_selesai'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }

      $simpan=Transaksi::create([
        'id_pelanggan'=>$request->id_pelanggan,
        'id_petugas'=>$request->id_petugas,
        'tgl_trans'=>$request->tgl_trans,
        'tgl_selesai'=>$request->tgl_selesai
      ]);
      $status=1;
      $message="Transaksi Berhasil Ditambahkan";
      if($simpan){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }


    public function update($id,Request $request){
      if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
          'id_pelanggan'=>'required',
          'id_petugas'=>'required',
          'tgl_trans'=>'required',
          'tgl_selesai'=>'required'
        ]
    );

      if($validator->fails()){
      return Response()->json($validator->errors());
    }

      $ubah=Transaksi::where('id',$id)->update([
        'id_pelanggan'=>$request->id_pelanggan,
        'id_petugas'=>$request->id_petugas,
        'tgl_trans'=>$request->tgl_trans,
        'tgl_selesai'=>$request->tgl_selesai
      ]);
      $status=1;
      $message="Transaksi Berhasil Diubah";
      if($ubah){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }



    public function destroy($id){
      if(Auth::user()->level=="petugas"){
      $hapus=Transaksi::where('id',$id)->delete();
      $status=1;
      $message="Transaksi Berhasil Dihapus";
      if($hapus){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }














  // detail transaksi
    public function simpan(Request $request){
      if(Auth::user()->level=="petugas"){

      $validator=Validator::make($request->all(),
        [
          'id_trans'=>'required',
          'id_jenis'=>'required',
          'qty'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }
      $harga=DB::table('jenis_cuci')->where('id', $request->id_jenis)->first();
      $subtotal = ($harga->harga_kiloan * $request->qty);
      $simpan=Detail_trans::create([
        'id_trans'=>$request->id_trans,
        'id_jenis'=>$request->id_jenis,
        'subtotal'=>$subtotal,
        'qty'=>$request->qty
      ]);
      $status=1;
      $message="Detail Transaksi Berhasil Ditambahkan";
      if($simpan){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }

    public function ubah($id,Request $request){
      if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
          'id_trans'=>'required',
          'id_jenis'=>'required',
          'subtotal'=>'required',
          'qty'=>'required'
        ]
    );

      if($validator->fails()){
      return Response()->json($validator->errors());
    }

      $ubah=Detail_trans::where('id',$id)->update([
        'id_trans'=>$request->id_trans,
        'id_jenis'=>$request->id_jenis,
        'subtotal'=>$request->subtotal,
        'qty'=>$request->qty
      ]);
      $status=1;
      $message="Detail Transaksi Berhasil Diubah";
      if($ubah){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }

    public function hapus($id){
      if(Auth::user()->level=="petugas"){
      $hapus=Detail_trans::where('id',$id)->delete();
      $status=1;
      $message="Detail Transaksi Berhasil Dihapus";
      if($hapus){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan petugas']);
    }
  }








}
