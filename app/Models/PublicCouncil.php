<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicCouncil extends Model
{
    use HasFactory, Localizable;

    protected $localeModel = PublicCouncilLocale::class;
    protected $localableFields = ['text'];
    protected $keyType = 'integer';
}
