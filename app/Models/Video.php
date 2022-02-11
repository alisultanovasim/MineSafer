<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class Video extends Model
{
    use HasFactory , Localizable;
    protected $localeModel = VideoLocale::class;
    protected $localableFields = ['title'];
    protected $keyType = 'integer';
    protected $fillable = ['url'];

    protected $file = File::class;
    protected $key  = 'image_uuid';

     public function image()
     {
         return $this->belongsTo(File::class , 'image_uuid');
     }
}
