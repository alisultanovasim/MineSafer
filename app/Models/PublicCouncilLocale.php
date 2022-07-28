<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicCouncilLocale extends Model
{
    use HasFactory, UsesUuid;

    protected $keyType = 'string';
    protected $fillable = ['text', 'local', 'public_council_id'];
}
