<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // function store
    public function store(Request $request)
    {
        // validator make
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:50',
            'content' => 'required',
        ],
            [
                'title.required' => 'Judul masi kosong silahkan masukan judul anda',
                'content.required' => 'Content masi kosong silahkan masukan conten anda',
                'title.min' => 'Masukan panjang kalimat lebih dari 3 kata',
                'title.max' => 'Masukan panjang kalimat kurang dari 50 kata',
            ]
        );
        //  kondisi pengecekan apakah isian benar apa salah
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'message' => 'Ada kesalahan dalam menginput silahkan cek lagi',
                'data' => $validator->errors(),
            ], 401);
        } else {
            // aplot ke dalam database
            $post = Post::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
            if ($post) {
                return response()->json([
                    'succes' => true,
                    'message' => 'Data berhasil di inputkan',
                ], 200);
            } else {
                return response()->json([
                    'succes' => false,
                    'message' => 'Data tidak terinputkan',
                ], 401);
            }
        }
    }
    public function index()
    {
        $data_post = Post::latest()->get();
        return response()->json(
            [
                'sucsses' => true,
                'pesan' => 'data berhasil diambil',
                'data' => $data_post,
            ],
            200
        );
    }
    public function cariId($id)
    {
        $post = Post::find($id);
        if ($post) {
            return response()->json([
                'sucsses' => true,
                'pesan' => "data dengan id " . strval($id),
                'data' => $post,
            ], 200);
        } else {
            return response()->json([
                'sucsses' => false,
                'pesan' => 'data tidak ditemukan',
                'data' => '',
            ], 401);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // cek id ini harus integer dan terisi dengan tidak boleh di bawah nol
            'id' => 'required|integer|min:0',
            'title' => 'required|min:3|max:50',
            'content' => 'required',
        ], [
            'title.required' => 'judul harus di isi',
            'content.required' => 'Content harus di isi',
            'title.min' => 'judul harus di isi lebih dari 3 kata',
            'title.max' => 'judul harus di isi kurang dari 50 kata',
            'id.required' => 'id harus di isi',
            'id.min' => 'id tidak boleh nilai minus (-)',
            'id.integer' => 'id harus berbentuk angka',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'sucsses' => false,
                'pesan' => "Ada Kesalahan dalam menginputkan",
                'data' => $validator->errors(),
            ], 401);
        } else {
            $data_post = Post::whereId($request->input('id'))->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);
            if ($data_post) {
                return response()->json([
                    'succses' => true,
                    'pesan' => 'Data berhasil dikirim',
                    //tampilkan data yang sudah di update
                    'data' => Post::find($request->input('id')),
                ], 200);
            } else {
                return response()->json([
                    'succses' => false,
                    'pesan' => 'maaf data tidak ada',
                    'data' => '',
                ], 401);
            }
        }
    }
    public function delete($id)
    {
        // batasi inputan id
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:0',
        ], [
            'id.required' => 'id harus di isi',
            'id.min' => 'id tidak boleh nilai minus (-)',
            'id.integer' => 'id harus berbentuk angka',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'pesan' => 'Ada kesalahan dalam menginputkan',
                'data' => $validator->errors(),
            ], 401);
        } else {
            $post = Post::findOrFail($id); // digunakan untuk membuat halaman 404 apabila id tidak ditemukan
            $post_hapus = $post->delete();
            if ($post_hapus) {
                return response()->json([
                    'succes' => true,
                    'pesan' => 'data berhasil dihapus',
                ], 200);
            } else {
                return response()->json([
                    'succes' => false,
                    'pesan' => 'Data tidak dapat dihapus atau tidak ditemukan',
                ], 401);
            }
        }
    }
}
