<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


  Route::post('register', 'PetugasController@register');
  Route::post('login', 'PetugasController@login');
  Route::get('/', function(){
    return Auth::user()->level;
  })->middleware('jwt.verify');

  Route::get('user', 'PetugasController@getAuthenticatedUser')->middleware('jwt.verify');


  // // Pelanggan
  // Route::get('/film','FilmController@index')->middleware('jwt.verify');
  Route::post('/simpan_pelanggan','PelangganController@store')->middleware('jwt.verify');
  Route::put('/ubah_pelanggan/{id}','PelangganController@update')->middleware('jwt.verify');
  Route::get('/tampil_pelanggan','PelangganController@tampil_pelanggan')->middleware('jwt.verify');
  Route::delete('/hapus_pelanggan/{id}','PelangganController@destroy')->middleware('jwt.verify');


  // // Jenis Cuci
  // Route::get('/anggota','AnggotaController@index')->middleware('jwt.verify');
  Route::post('/simpan_jenis','JenisController@store')->middleware('jwt.verify');
  Route::put('/ubah_jenis/{id}','JenisController@update')->middleware('jwt.verify');
  Route::get('/tampil_jenis','JenisController@tampil_jenis')->middleware('jwt.verify');
  Route::delete('/hapus_jenis/{id}','JenisController@destroy')->middleware('jwt.verify');


  // // // transaksi
  // // Route::get('/peminjaman','PinjamController@index')->middleware('jwt.verify');
  Route::post('/simpan_trans','TransaksiController@store')->middleware('jwt.verify');
  Route::put('/ubah_trans/{id}','TransaksiController@update')->middleware('jwt.verify');
  Route::get('/tampil_trans','TransaksiController@tampil_trans')->middleware('jwt.verify');
  Route::delete('/hapus_trans/{id}','TransaksiController@destroy')->middleware('jwt.verify');


  // // // detail_transaksi
  Route::post('/simpan_detail','TransaksiController@simpan')->middleware('jwt.verify');
  Route::put('/ubah_detail/{id}','TransaksiController@ubah')->middleware('jwt.verify');
  // Route::get('/tampil_tiket','TayangController@tampil_tiket')->middleware('jwt.verify');
  Route::delete('/hapus_detail/{id}','TransaksiController@hapus')->middleware('jwt.verify');


  Route::get('/report/{tgl_trans}/{tgl_selesai}','TransaksiController@report')->middleware('jwt.verify');


  // Route::group(['middleware'=>'cek_login'], function(){
  //   Route::get('/dashboard','DashController@index');
  //   // if(Session::get('status')=='admin'){
  //   //   Route::get()
  //   // }
  // });
