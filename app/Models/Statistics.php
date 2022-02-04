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
    protected $fillable = ['tank', 'clean_area', 'unexplosive', 'pedestrian','type','region_id'];
}
