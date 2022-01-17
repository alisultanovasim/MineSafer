<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;

class AboutController extends Controller
{
    use ApiResponder, Paginatable;
    private $perPage;
    public function index()
    {
        if (auth()->check()) {
            $about = About::with('image' , 'locales', 'category');
        } else {
            $about = About::with('image' , 'locale', 'category');
        }
        return $this->dataResponse($about->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (auth()->check()) {
            $about = About::with('image', 'category', 'locales')->findOrFail($id);
        } else {
            $about = About::with('image', 'category', 'locale')->findOrFail($id);
        }
        return $this->dataResponse($about);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $about_id = null;
        DB::transaction(function () use ($request, &$about_id) {

            $about = new About();

            $about->image_uuid = $request->image_uuid;
            $about->about_category_id = $request->about_category_id;
            $about->created_at = now();
            $about->save();
            $about->setLocales($request->locales);

            $about_id = $about->id;
        });

        return $this->dataResponse(['about_id' => $about_id], 201);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $about = About::findOrFail($id);

            $about->fill($request->only([
                'about_category_id',
                'image_uuid'
            ]));
            $about->save();

            $about->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $about = About::findOrFail($id);

            $about->locales()->delete();

            $about->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'about_category_id' => 'required|numeric|exists:about_categories,id',
            'image_uuid' => 'required|exists:files,id',
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'about_category_id.required' => 'Kateqoriya adı mütləqdir',
            'about_category_id.exists' => 'Kateqoriya id mövcud deyil',
            'image_uuid.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
