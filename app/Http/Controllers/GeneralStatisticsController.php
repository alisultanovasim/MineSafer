<?php

namespace App\Http\Controllers;

use App\Models\GeneralStatistics;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralStatisticsController extends Controller
{
    use ApiResponder;

    public function index()
    {
        $statistics = GeneralStatistics::first();

        return $this->dataResponse($statistics);
    }


    public function show($id)
    {
        $statistic = GeneralStatistics::findOrFail($id);

        return $this->dataResponse($statistic);
    }


    public function store(Request $request)
    {
        GeneralStatistics::query()->delete();
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request) {
            $statistic = new GeneralStatistics();
            $statistic->clean_area_year = $request->clean_area_year;
            $statistic->clean_area_week = $request->clean_area_week;
            $statistic->clean_area_monthly = $request->clean_area_monthly;
            $statistic->unexplosive_year = $request->unexplosive_year;
            $statistic->unexplosive_week = $request->unexplosive_week;
            $statistic->unexplosive_monthly = $request->unexplosive_monthly;
            $statistic->pedestrian_year = $request->pedestrian_year;
            $statistic->pedestrian_week = $request->pedestrian_week;
            $statistic->pedestrian_monthly = $request->pedestrian_monthly;
            $statistic->tank_year = $request->tank_year;
            $statistic->tank_week = $request->tank_week;
            $statistic->tank_monthly = $request->tank_monthly;
            $statistic->created_at = now();
            $statistic->save();
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules($id), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $statistic = GeneralStatistics::findOrFail($id);
            $statistic->clean_area_year = $request->clean_area_year;
            $statistic->clean_area_week = $request->clean_area_week;
            $statistic->clean_area_monthly = $request->clean_area_monthly;
            $statistic->unexplosive_year = $request->unexplosive_year;
            $statistic->unexplosive_week = $request->unexplosive_week;
            $statistic->unexplosive_monthly = $request->unexplosive_monthly;
            $statistic->pedestrian_year = $request->pedestrian_year;
            $statistic->pedestrian_week = $request->pedestrian_week;
            $statistic->pedestrian_monthly = $request->pedestrian_monthly;
            $statistic->tank_year = $request->tank_year;
            $statistic->tank_week = $request->tank_week;
            $statistic->tank_monthly = $request->tank_monthly;
            $statistic->save();
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $statistic = GeneralStatistics::findOrFail($id);
            $statistic->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules($id = null): array
    {
        return [
            'clean_area_year' => 'required|integer',
            'clean_area_week' => 'required|integer',
            'clean_area_monthly' => 'required|integer',
            'unexplosive_year' => 'required|integer',
            'unexplosive_week' => 'required|integer',
            'unexplosive_monthly' => 'required|integer',
            'unexplosive_monthly' => 'required|integer',
            'pedestrian_year' => 'required|integer',
            'pedestrian_week' => 'required|integer',
            'pedestrian_monthly' => 'required|integer',
            'pedestrian_monthly' => 'required|integer',
            'tank_year' => 'required|integer',
            'tank_week' => 'required|integer',
            'tank_monthly' => 'required|integer',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'integer' => ':attribute sayı rəqəm olmalıdır',
            'required' => ':attribute sayı mütləq olmalıdır',
        ];
    }
}
