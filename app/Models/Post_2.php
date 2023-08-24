<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_2 extends Model
{
    use HasFactory;

    // yang bisa di ubah dalam database yang di buat
    protected $fillable = [
        'image',
        'title',
        'content',
    ];
}
