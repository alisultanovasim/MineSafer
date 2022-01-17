<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class About extends Model
{
    use HasFactory, Localizable;

    protected $keyType = 'integer';
    protected $fillable = ['image_uuid', 'about_category_id'];
    protected $localeModel = AboutLocale::class;

    protected $localableFields = ['text'];

    protected $file = File::class;
    protected $key = 'image_uuid';

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_uuid');
    }

    // public function abouts()
    // {
    //     return $this->hasMany(AboutCategory::class, 'about_category_id')->with('image' , 'locales');
    // }


    // public function aboutLocales()
    // {
    //     return $this->hasMany(AboutLocale::class , 'about_id');
    // }

    public function category()
    {
        return $this->belongsTo(AboutCategory::class, 'about_category_id', 'id')->with('locale');
    }

    public function categories()
    {
        return $this->belongsTo(AboutCategory::class, 'about_category_id', 'id')->with('locales');
    }

}
