<?php

namespace App\Http\Controllers;

use App\Models\TrainingCategory;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingCategoryController extends Controller
{
    use ApiResponder;

    public function index()
    {
        if (auth()->check()) {
            $categories = TrainingCategory::with('locales')->get();
        } else {
            $categories = TrainingCategory::with('locale')->get();
        }
        return $this->dataResponse($categories);
    }


    public function show($id)
    {

        if (auth()->check()) {
            $category = TrainingCategory::with('locales')->findOrFail($id);
        } else {
            $category = TrainingCategory::with('locale')->findOrFail($id);
        }
        return $this->dataResponse($category);
    }

    
    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules() , $this->customAttributes());

        $trainingCategory_id = null;
        DB::transaction(function () use ($request, &$trainingCategory_id) {
            $trainingCategory = new TrainingCategory;
            $trainingCategory->created_at = now();
            $trainingCategory->save();
            $trainingCategory->setLocales($request->input("locales"));
            $trainingCategory_id = $trainingCategory->id;
        });

        return $this->dataResponse(['trainingCategory_id' => $trainingCategory_id], 201);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules() , $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $trainingCategory = TrainingCategory::findOrFail($id);
            $trainingCategory->updated_at = now();
            $trainingCategory->save();
            $trainingCategory->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $trainingCategory = TrainingCategory::findOrFail($id);

            $trainingCategory->locales()->delete();

            $trainingCategory->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'locales.*.local' => 'required',
            'locales.*.name' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'locales.*.name.required' => 'Kateqoriya adı mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
