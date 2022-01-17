<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use App\Models\LeaderShip;
use Illuminate\Support\Facades\DB;

class LeaderShipController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $leaderships = LeaderShip::with('image', 'locales');
        } else {
            $leaderships = LeaderShip::with('image', 'locale');
        }
        return $this->dataResponse($leaderships->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (auth()->check()) {
            $leadership = LeaderShip::with('image', 'locales')->findOrFail($id);
        } else {
            $leadership = LeaderShip::with('image', 'locale')->findOrFail($id);
        }
        return $this->dataResponse($leadership);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request) {
            $leadership = new LeaderShip;
            $leadership->image_uuid = $request->image_uuid;
            $leadership->status = $request->status;
            $leadership->save();
            $leadership->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans("responses.ok"));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $leadership = LeaderShip::findOrFail($id);
            $leadership->image_uuid = $request->image_uuid;
            $leadership->status = $request->status;
            $leadership->save();
            $leadership->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $leadership = LeaderShip::findOrFail($id);

            $leadership->locales()->delete();

            $leadership->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }



    private function getValidationRules(): array
    {
        return [
            'image_uuid' => 'required|exists:files,id',
            'status' => 'required',
            'locales.*.local' => 'required',
            'locales.*.full_name' => 'required',
            'locales.*.position' => 'required',
            'locales.*.text' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'image_uuid.required' => 'İmage id mütləqdir',
            'image_uuid.exists' => 'İmage id mövcud deyil',
            'locales.*.full_name.required' => 'Ad Soyad mütləqdir',
            'locales.*.position.required' => 'Vəzifə mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir',
            'locales.*.text.required' => 'Text mütləqdir'

        ];
    }
}
