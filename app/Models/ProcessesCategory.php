<?php

namespace App\Models;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessesCategory extends Model
{
    use HasFactory, Localizable;

    protected $localeModel = ProcessesCategoryLocale::class;
    protected $keyType = 'integer';
    protected $localableFields = ['name'];
    protected $table = 'news_categories';

    public function processes()
    {
        return $this->hasMany(Process::class, 'processes_category_id')->with('locales');
    }

    public function process()
    {
        return $this->hasMany(Process::class, 'processes_category_id')->with('locale');
    }
}
