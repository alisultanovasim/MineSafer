<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityLocale extends Model
{
    use HasFactory , UsesUuid;
    protected $keyType = 'string';
    protected $fillable = ['quality_id', 'text', 'local'];

}
