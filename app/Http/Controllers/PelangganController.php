<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pelanggan;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class PelangganController extends Controller
{
    public function store(Request $request){
      if(Auth::user()->level=="admin"){
      $validator=Validator::make($request->all(),
        [
          'nama_pelanggan'=>'required',
          'telp'=>'required',
          'alamat'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }


      $simpan=Pelanggan::create([
        'nama_pelanggan'=>$request->nama_pelanggan,
        'telp'=>$request->telp,
        'alamat'=>$request->alamat
      ]);
      $status=1;
      $message="Pelanggan Berhasil Ditambahkan";
      if($simpan){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

    public function update($id,Request $request){
      if(Auth::user()->level=="admin"){
      $validator=Validator::make($request->all(),
        [
          'nama_pelanggan'=>'required',
          'telp'=>'required',
          'alamat'=>'required'
        ]
    );

      if($validator->fails()){
      return Response()->json($validator->errors());
    }

      $ubah=Pelanggan::where('id',$id)->update([
        'nama_pelanggan'=>$request->nama_pelanggan,
        'telp'=>$request->telp,
        'alamat'=>$request->alamat
      ]);
      $status=1;
      $message="Pelanggan Berhasil Diubah";
      if($ubah){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

    public function tampil_pelanggan(){
      if(Auth::user()->level=="admin"){
      $data_pelanggan=Pelanggan::get();
      $count=$data_pelanggan->count();
      $arr_data=array();
      foreach ($data_pelanggan as $dt_fl){
        $arr_data[]=array(
          'id' => $dt_fl->id,
          'nama_pelanggan' => $dt_fl->nama_pelanggan,
          'telp' => $dt_fl->telp,
          'alamat' => $dt_fl->alamat
        );
      }
      $status=1;
      return Response()->json(compact('status','count','arr_data'));
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

    public function destroy($id){
      if(Auth::user()->level=="admin"){
      $hapus=Pelanggan::where('id',$id)->delete();
      $status=1;
      $message="Pelanggan Berhasil Dihapus";
      if($hapus){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }
}
