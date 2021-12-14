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
            $pages = page::whereIsActive(1)->with('locales', 'subPages');
        } else {
            $pages = page::whereIsActive(1)->with('locale', 'subPage');
        }
        return $this->dataResponse($pages->simplePaginate($this->getPerPage()));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $page = page::findOrFail($id);

            $page->fill($request->only([
                'is_active',
                'key'
            ]));

            $page->save();

            $page->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $page_id = null;
        DB::transaction(function () use ($request, &$page_id) {
            $page = new Page();
            $page->fill($request->only([
                'is_active',
                'key'
            ]));

            $page->save();

            $page->setLocales($request->input("locales"));

            $page_id = $page->id;
        });

        return $this->dataResponse(['page_id' => $page_id], 201);
    }

    public function show($id)
    {
        if (auth()->check()) {
            $page = Page::with('locales')->findOrFail($id);
        } else {
            $page = Page::with('locale')->findOrFail($id);
        }
        return $this->dataResponse($page);
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


    private function getValidationRules(): array
    {
        return [
            'is_active' => 'required|boolean',
            'locales.*.local' => 'required',
            'locales.*.name' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'is_active.required' => 'Status',
            'is_active.boolean' => 'Status 1 və ya 0 ola bilər',
            'locales.*.name.required' => 'Sehifə adı mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
