<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    use HasFactory, Localizable;

    protected $localeModel = StatisticsLocale::class;
    protected $localableFields = ['title'];
    protected $keyType = 'integer';
    protected $fillable = ['clean_area', 'region_id', 'image_uuid'];

    protected $file = File::class;
    protected $key = 'image_uuid';

    public function image()
    {
        return $this->belongsTo(File::class, 'image_uuid');
    }
}
