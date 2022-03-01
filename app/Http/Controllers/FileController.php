<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;


class FileController extends Controller
{
    use ApiResponder;

    private $fileService;


    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }


    public function multiUpload(Request $request)
    {
        $this->validate($request, [
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,svg',
        ]);

        $arr = [];
        foreach ($request->file('images') as $file) {

            $image = $this->fileService->uploadFile($file);

            array_push($arr, $image->getkey());
        }
        return response(['images' => $arr]);
    }


    public function uploadSingleImage(Request $request): JsonResponse
    {
        $this->validate($request, [
            'image_uuid' => 'required|image|mimes:jpg,jpeg,png,svg'
        ]);

        $image = $this->fileService->uploadFile($request->file('image_uuid'));

        return $this->dataResponse(['image__uuid' => $image->getKey()]);
    }


    public function uploadFile(Request $request): JsonResponse
    {
        $this->validate($request, [
            'file_uuid' => 'required|file|mimes:pdf,doc,docx'
        ]);

        $file = $this->fileService->uploadFile($request->file('file_uuid'));
        return $this->dataResponse(['file_uuid' => $file->getKey()]);
    }


    public function uploadVideo(Request $request): JsonResponse
    {
        $this->validate($request, [
            'video_uuid' => 'required|max:800000'
        ]);

        $video = $this->fileService->uploadFile($request->file('video_uuid'));
        return $this->dataResponse(['video_uuid' => $video->getKey()]);
    }

    // public function uploadSingleIcon(Request $request): JsonResponse
    // {
    //     $this->validate($request, [
    //         'icon_uuid' => 'required'
    //     ]);

    //     $icon = $this->fileService->uploadFile($request->file('icon_uuid'));

    //     return $this->dataResponse(['icon__uuid' => $icon->getKey()]);
    // }

}
