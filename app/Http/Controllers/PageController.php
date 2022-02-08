<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $pages = page::with('locales', 'image', 'subPages');
        } else {
            $pages = page:: with('locale', 'image', 'subPage');
        }
        return $this->dataResponse($pages->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (!auth()->check()) {
            $page = Page::where('is_active', 1)->with('locale', 'image', 'subPage')->findOrFail($id);
        } else {
            $page = Page::with('locales', 'image', 'subPages')->findOrFail($id);
        }
        return $this->dataResponse($page);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules($id), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $page = page::findOrFail($id);

            $page->fill($request->only([
                'is_active',
                'key',
                'image_uuid'
            ]));

            $page->save();

            $page->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $page_id = null;
        DB::transaction(function () use ($request, &$page_id) {
            $page = new Page();
            $page->fill($request->only([
                'is_active',
                'key',
                'image_uuid'
            ]));

            $page->save();

            $page->setLocales($request->input("locales"));

            $page_id = $page->id;
        });

        return $this->dataResponse(['page_id' => $page_id], 201);
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $page = Page::findOrFail($id);
            $page->subPage()->delete();
            $page->locales()->delete();
            $page->delete();
        });
        return $this->successResponse(trans('responses.ok'));
    }


    private function getValidationRules($id = null): array
    {
        // dd($id);
        return [
            'image_uuid' => 'required|exists:files,id',
            'key' => 'unique:pages,key,' . $id,
            'is_active' => 'required|boolean',
            'locales.*.local' => 'required',
            'locales.*.name' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'image_uuid.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
            'is_active.required' => 'Status',
            'is_active.boolean' => 'Status 1 və ya 0 ola bilər',
            'locales.*.name.required' => 'Sehifə adı mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
