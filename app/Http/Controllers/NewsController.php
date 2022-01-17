<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $news = News::with('locales', 'image', 'categories');
        } else {
            $news = News::with('locale', 'image', 'category');
        }
        return $this->dataResponse($news->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (auth()->check()) {
            $news = News::with('image', 'locales', 'categories')->findOrFail($id);
        } else {
            $news = News::with('image', 'locale', 'category')->findOrFail($id);
        }
        return $this->dataResponse($news);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $news_id = null;
        DB::transaction(function () use ($request, &$news_id) {

            $news = new News;
            $news->created_at = now();
            $news->image_uuid = $request->image_uuid;
            $news->is_active = $request->is_active;
            $news->news_category_id = $request->news_category_id;
            $news->position = $request->position;
            $news->date = $request->date;
            $news->save();

            $news->setLocales($request->input("locales"));

            $news_id = $news->id;
        });

        return $this->dataResponse(['news_id' => $news_id], 201);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $news = News::findOrFail($id);
            $news->updated_at = now();
            $news->image_uuid = $request->image_uuid;
            $news->is_active = $request->is_active;
            $news->news_category_id = $request->news_category_id;
            $news->position = $request->position;
            $news->date = $request->date;
            $news->save();

            $news->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $news = News::findOrFail($id);

            $news->locales()->delete();

            $news->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }

    private function getValidationRules(): array
    {
        return [
            'news_category_id' => 'required|numeric|exists:news_categories,id',
            'category' => 'required',
            'date' => 'required',
            'image_uuid' => 'required|exists:files,id',
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
            'locales.*.title' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'category.required' => 'Kategoriya mütləqdir',
            'date.required' => 'Tarix mütləqdir',
            'news_category_id.required' => 'Kateqoriya adı mütləqdir',
            'news_category_id.exists' => 'Kateqoriya id mövcud deyil',
            'image_uuid.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.title.required' => 'Başlıq mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
