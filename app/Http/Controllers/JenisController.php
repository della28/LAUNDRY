<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jenis;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class JenisController extends Controller
{
    public function store(Request $request){
      if(Auth::user()->level=="admin"){
      $validator=Validator::make($request->all(),
        [
          'nama_jenis'=>'required',
          'harga_kiloan'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }


      $simpan=Jenis::create([
        'nama_jenis'=>$request->nama_jenis,
        'harga_kiloan'=>$request->harga_kiloan
      ]);
      $status=1;
      $message="Jenis Cuci Berhasil Ditambahkan";
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
          'nama_jenis'=>'required',
          'harga_kiloan'=>'required'
        ]
    );

      if($validator->fails()){
      return Response()->json($validator->errors());
    }

      $ubah=Jenis::where('id',$id)->update([
        'nama_jenis'=>$request->nama_jenis,
        'harga_kiloan'=>$request->harga_kiloan
      ]);
      $status=1;
      $message="Jenis Cuci Berhasil Diubah";
      if($ubah){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    } else{
      return response()->json(['status'=>'anda bukan admin']);
    }
  }

    public function tampil_jenis(){
      if(Auth::user()->level=="admin"){
      $data_jenis=Jenis::get();
      $count=$data_jenis->count();
      $arr_data=array();
      foreach ($data_jenis as $dt_jn){
        $arr_data[]=array(
          'id' => $dt_jn->id,
          'nama_jenis' => $dt_jn->nama_jenis,
          'harga_kiloan' => $dt_jn->harga_kiloan
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
      $hapus=Jenis::where('id',$id)->delete();
      $status=1;
      $message="Jenis Cuci Berhasil Dihapus";
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
