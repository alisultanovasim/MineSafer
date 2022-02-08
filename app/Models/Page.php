<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubPage;
use App\Models\File;

class Page extends Model
{
    use HasFactory, Localizable;
    protected $keyType = 'integer';
    protected $fillable = ['is_active', 'key' , 'image_uuid'];
    protected $localeModel = PageLocale::class;

    protected $localableFields = ['name', 'local'];

    public $timestamps = false;

    public function subPages()
    {
        return $this->hasMany(SubPage::class , 'page_id')->with('locales');
    }

    public function subPage()
    {
        return $this->hasMany(SubPage::class , 'page_id')->where('is_active', 1)->with('locale');
    }

    public function image()
    {
        return $this->belongsTo(File::class , 'image_uuid');
    }
}
