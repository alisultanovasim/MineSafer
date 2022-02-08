<?php

namespace App\Http\Controllers;

use App\Models\AboutCategory;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutCategoryController extends Controller
{
    use ApiResponder;

    public function index()
    {
        if (auth()->check()) {
            $about = AboutCategory::with('abouts');
        } else {
            $about = AboutCategory::with('about');
        }

        return $this->dataResponse($about->orderByDesc('date')->get());
    }


    public function show($id)
    {
    }


    public function store(Request $request)
    {

        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        AboutCategory::insert([
            'date' => $request->date,
            'created_at' => now()
        ]);

        return $this->successResponse(trans('ok'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        AboutCategory::findOrFail($id)->update([
            'date' => $request->date,
            'updated_at' => now()
        ]);

        return $this->successResponse(trans('ok'));
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $category = AboutCategory::findOrFail($id);
            $category->abouts()->delete();
            $category->deletes()->delete();
            $category->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'date' => 'required|unique:about_categories|numeric',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'date' => 'Tarix mütləqdir',
        ];
    }
}
