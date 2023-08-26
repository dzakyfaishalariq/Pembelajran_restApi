<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post_2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostControllerRestFull extends Controller
{
    public function index()
    {
        $post = Post_2::latest()->paginate(5);

        return new PostResource(true, 'data berhasil diambil', $post);
    }
    public function tambahData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'title' => 'required|min:3|max:50',
            'content' => "required",
        ], [
            'image.required' => 'data harus di isi',
            'image.image' => 'data harus gambar',
            'image.mimes' => 'data harus format png,jpg,jpeg,gif,svg',
            'image.max' => 'data harus berukuran 2048',
            'title.required' => 'data harus di isi',
            'title.min' => 'data harus lebih panjang dari 3 karakter',
            'title.max' => 'data harus kurang dari panjang 50 karakter',
            'content.required' => 'data harus di isi',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'ada kesalahan input',
                'data' => $validator->errors(),
            ], 422);
        }

        $applod_image = $request->file('image');
        $applod_image->storeAs('public/posts', $applod_image->hashName());
        $post = Post_2::create([
            'image' => $applod_image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return new PostResource(true, 'data berhasil dikirim', $post);
    }
    public function cariData(Post_2 $post_2)
    {
        $data = [
            'id' => $post_2->id,
            'image' => $post_2->image,
            'title' => $post_2->title,
            'content' => $post_2->content,
        ];
        return new PostResource(true, 'data berhasil di temukan', $data);
    }
    public function updateData(Request $request, Post_2 $post_2)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:png,jpg,jpeg,gif,svg|max:2048',
            'title' => 'required|min:3|max:50',
            'content' => "required",
        ], [
            'image.required' => 'data harus di isi',
            'image.image' => 'data harus gambar',
            'image.mimes' => 'data harus format png,jpg,jpeg,gif,svg',
            'image.max' => 'data harus berukuran 2048',
            'title.required' => 'data harus di isi',
            'title.min' => 'data harus lebih panjang dari 3 karakter',
            'title.max' => 'data harus kurang dari panjang 50 karakter',
            'content.required' => 'data harus di isi',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'maaf ada kesalahan input',
                'data' => $validator->errors(),
            ], 422);
        }
        // aplod image
        // chek image apakah ada atau tidak dalam inputan
        if ($request->hasFile('image')) {
            // jika ada
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
            // hapus gambar lama
            Storage::delete('public/posts/' . $post_2->image);
            // update data
            $post_2->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content,
            ]);
        } else {
            $post_2->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }
        return new PostResource(true, 'data berhasil diubah', $post_2);
    }
    public function deleteData(Post_2 $post_2)
    {
        Storage::delete('public/posts/' . $post_2->image);
        $post_2->delete();
        return new PostResource(true, 'DATA BERHASIL DIHAPUS !!', null);
    }
}
