<?php

namespace App\Http\Controllers;

use App\Models\Statistics;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    use ApiResponder;

    public function index()
    {
        if (auth()->check()) {
            $statistics = Statistics::with('locales');
        } else {
            $statistics = Statistics::with('locale');
        }
        if (request()->filled('type')) {
            $statistics = $statistics->where('type', request()->get('type'));
        }

        if (request()->filled('region_id')) {
            $statistics = $statistics->where('region_id', request()->get('region_id'));
        }
        return $this->dataResponse($statistics->get());
    }


    public function show($id)
    {
        if (auth()->check()) {
            $statistic = Statistics::with('locales')->findOrFail($id);
        } else {
            $statistic = Statistics::with('locale')->findOrFail($id);
        }
        return $this->dataResponse($statistic);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request) {
            $statistic = new Statistics;
            $statistic->clean_area = $request->clean_area;
            $statistic->region_id = $request->region_id;
            $statistic->created_at = now();
            $statistic->save();
            $statistic->setLocales($request->locales);
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules($id), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $statistic = Statistics::findOrFail($id);
            $statistic->clean_area = $request->clean_area;
            $statistic->region_id = $request->region_id;
            $statistic->updated_at = now();
            $statistic->save();
            $statistic->setLocales($request->locales);
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $statistic = Statistics::findOrFail($id);
            $statistic->locales()->delete();
            $statistic->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules($id = null): array
    {
        return [
            'region_id' => 'integer',
            'clean_area' => 'integer',
            'locales.*.local' => 'required',
            'locales.*.title' => 'required'
        ];
    }

    public function customAttributes(): array
    {
        return [
            'clean_area.integer' => 'Təmizlənən ərazi sayı rəqəm olmalıdır',
            'locales.*.local.required' => 'Dil seçimi mütləqdir',
            'locales.*.title.required' => 'Başlıq mütləqdir',
            'region_id' => 'Region ID mütləqdir'
        ];
    }
}
