<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoFolder;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $photos = PhotoFolder::with('locales', 'photos')->orderBy('created_at', 'desc')->get();
        } else {
            $photos = PhotoFolder::with('locale', 'photos')->orderBy('created_at', 'desc')->get();
        }
        return $this->dataResponse($photos);
    }


    public function show($id)
    {
        if (auth()->check()) {
            $photoFolder = Photo::where('id', $id)->with('images', 'folders')->firstOrFail();
        } else {
            $photoFolder = Photo::where('id', $id)->where()->with('images', 'folder')->firstOrFail();
        }
        return $this->dataResponse($photoFolder);
    }


    public function store(Request $request)
    {
        $folder_id = $request->photo_folder_id;
        $this->validate($request, $this->storeVal($folder_id), $this->customAttributes());

        // foreach ($request->images as $image) {
        $photo = new Photo;
        $photo->created_at = now();
        $photo->image_uuid = $request->image_uuid;
        // $photo->order = $request->order;
        $photo->photo_folder_id = $request->photo_folder_id;
        $photo->save();
        // }
        return $this->successResponse(trans('responses.ok'));
    }


    private function storeVal($folder_id = null): array
    {
        return [
            'photo_folder_id' => 'required|numeric|exists:photo_folders,id',
            'image_uuid' => 'required|exists:files,id',
            // 'order' => 'required|numeric|unique:photos',

            // 'order' => [
            //     'numeric',
            //     'required',
            //     Rule::unique('photos', 'order')->where(function ($query) use ($folder_id) {
            //         $query->where('photo_folder_id', $folder_id);
            //     })
            // ],
        ];
    }


    public function update(Request $request, $id)
    {
        $folder_id = $request->photo_folder_id;

        $this->validate($request, $this->getValidationRules($id, $folder_id), $this->storeCustom());

        // foreach ($request->images as $image) {

        $photo = Photo::findOrFail($id);
        $photo->updated_at = now();
        $photo->image_uuid = $request->image_uuid;
        // $photo->order = $request->order;
        $photo->photo_folder_id = $request->photo_folder_id;
        $photo->save();
        // }

        return $this->successResponse(trans('responses.ok'));
    }


    private function getValidationRules($id = null, $folder_id = null): array
    {
        return [
            'photo_folder_id' => 'required|numeric|exists:photo_folders,id',
            'image_uuid' => 'required|exists:files,id',
            // 'order' => 'required|numeric|unique:photos,order,' . $id,

            // 'order' => [
            //     'numeric',
            //     'required',
            //     Rule::unique('photos', 'order')->where(function ($query) use ($folder_id, $id) {
            //         $query->where([
            //             ['photo_folder_id', $folder_id],
            //             ['order', '!=', $id],
            //         ]);
            //     })
            // ],
        ];
    }


    public function destroy($id)
    {
        Photo::findOrFail($id)->delete();
        return $this->successResponse(trans('responses.ok'));
    }


    public function storeCustom(): array
    {
        return [
            'photo_folder_id.required' => 'Photo folder adı mütləqdir',
            'photo_folder_id.exists' => 'Photo folder id mövcud deyil',
            'image_uuid.*.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
        ];
    }


    public function customAttributes(): array
    {
        return [
            'photo_folder_id.required' => 'Photo folder adı mütləqdir',
            'photo_folder_id.exists' => 'Photo folder id mövcud deyil',
            'image_uuid.*.required' => 'Image id mütləqdir',
            'image_uuid.exists' => 'Image id mövcud deyil',
        ];
    }
}
