<?php

namespace App\Models;

use App\Traits\FileRelation;
use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class LeaderShip extends Model
{
    use HasFactory , Localizable , FileRelation;
    protected $localeModel = LeaderShipLocale::class;
    protected $keyType = 'integer';
    protected $localableFields = ['full_name' , 'position','text'];
    protected $fillable = ['status', 'image_uuid'];

    protected $file = File::class;
    protected $key  = 'image_uuid';

    public function image()
    {
        return $this->belongsTo(File::class , 'image_uuid');
    }

}

