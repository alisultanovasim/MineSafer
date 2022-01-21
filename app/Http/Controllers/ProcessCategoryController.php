<?php

namespace App\Http\Controllers;

use App\Models\ProcessesCategory;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessCategoryController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $categories = ProcessesCategory::with('locales', 'processes');
        } else {
            $categories = ProcessesCategory::with('locale', 'process');
        }
        return $this->dataResponse($categories->simplePaginate($this->getPerPage()));
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $processCategory_Id = null;
        DB::transaction(function () use ($request, &$processCategory_Id) {
            $processCategory = new ProcessesCategory();
            $processCategory->created_at = now();
            $processCategory->save();
            $processCategory->setLocales($request->input("locales"));
            $processCategory_Id = $processCategory->id;
        });

        return $this->dataResponse(['processCategory_Id' => $processCategory_Id], 201);
    }


    public function show($id)
    {
        if (auth()->check()) {
            $category = ProcessesCategory::with('locales', 'processes')->findOrFail($id);
        } else {
            $category = ProcessesCategory::with('locale', 'process')->findOrFail($id);
        }
        return $this->dataResponse($category);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $processCategory = ProcessesCategory::findOrFail($id);
            $processCategory->updated_at = now();
            $processCategory->save();
            $processCategory->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $category = ProcessesCategory::findOrFail($id);

            $category->locales()->delete();

            $category->delete();
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
            'locales.*.name.required' => 'Ad mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
