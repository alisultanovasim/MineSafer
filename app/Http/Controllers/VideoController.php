<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{

    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $videos = Video::with('locales','image');
        } else {
            $videos = Video::with('locale','image');
        }
        return $this->dataResponse($videos->paginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (auth()->check()) {
            $video = Video::with('locales','image')->findOrFail($id);
        } else {
            $video = Video::with('locale','image')->findOrFail($id);
        }
        return $this->dataResponse($video);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $video_id = null;
        DB::transaction(function () use ($request, &$video_id) {
            $video = new Video;
            $video->image_uuid = $request->image_uuid;
            $video->url = $request->url;
            $video->save();

            $video->setLocales($request->input("locales"));

            $video_id = $video->id;
        });

        return $this->dataResponse(['video_id' => $video_id], 201);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $video = Video::findOrFail($id);
            $video->image_uuid = $request->image_uuid;
            $video->url = $request->url;
            $video->save();
            $video->setLocales($request->input("locales"));
        });
        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $video = Video::findOrFail($id);
            $video->locales()->delete();
            $video->delete();
        });
        return $this->successResponse(trans("responses.ok"));
    }

    private function getValidationRules(): array
    {
        return [
            'url' => 'required|url',
            'image_uuid' => 'required|exists:files,id',
            'locales.*.local' => 'required',
            'locales.*.title' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'image_uuid.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
            'url.required' => 'Url mütləqdir',
            'url.url' => 'Url düzgün deyil',
            'locales.*.title.required' => 'Başlıq mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
