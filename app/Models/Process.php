<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Process extends Model
{
    use HasFactory , Localizable;
    protected $localeModel = ProcessLocale::class;
    protected $localableFields = ['text'];
    protected $keyType = 'integer';
    protected $fillable = ['text' , 'image_uuid','processes_category_id'];

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_uuid');
    }

    public function category()
    {
        return $this->belongsTo(ProcessesCategory::class , 'processes_category_id' , 'id')->with('locale');
    }

    public function categories()
    {
        return $this->belongsTo(ProcessesCategory::class , 'processes_category_id' , 'id')->with('locales');
    }
}
