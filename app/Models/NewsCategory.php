<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\News;

class NewsCategory extends Model
{
    use HasFactory, Localizable;
    protected $localeModel = NewsCategoryLocale::class;
    protected $keyType = 'integer';
    protected $localableFields = ['name'];
    protected $table = 'news_categories';

    public function news()
    {
        return $this->hasMany(News::class , 'news_category_id')->with('locales');
    }

    public function oneNews()
    {
        return $this->hasMany(News::class , 'news_category_id')->with('locale','image');
    }
}
