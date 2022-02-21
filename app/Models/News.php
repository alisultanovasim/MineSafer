<?php

namespace App\Models;

use App\Traits\FileRelation;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NewsCategory;
use App\Models\File;

class News extends Model
{
    use HasFactory, Localizable , FileRelation;

    protected $localeModel = NewsLocale::class;
    protected $keyType = 'integer';
    protected $localableFields = ['text' , 'title'];
    protected $fillable = ['text', 'news_category_id' , 'is_active' ,'category','date'];

    protected $file = File::class;
    protected $key  = 'image_uuid';

    public function category()
    {
        return $this->belongsTo(NewsCategory::class , 'news_category_id' , 'id')->with('locale');
    }

    public function categories()
    {
        return $this->belongsTo(NewsCategory::class , 'news_category_id' , 'id')->with('locales');
    }

     public function image()
     {
         return $this->belongsTo(File::class , 'image_uuid');
     }
}
