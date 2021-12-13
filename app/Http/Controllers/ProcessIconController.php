<?php

namespace App\Http\Controllers;

use App\Models\ProcessIcon;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ProcessIconLocale;

class ProcessIconController extends Controller
{
    use ApiResponder;

    public function index()
    {
        $ProcessIcons = ProcessIcon::query()->with('image', 'icon', 'locales')->get();
        return response($ProcessIcons);
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $processIcon_id = null;

        DB::transaction(function () use ($request, &$processIcon_id) {
            $processIcon = new ProcessIcon();
            $processIcon->fill($request->only([
                'icon_uuid',
                'image_uuid'
            ]));

            $processIcon->save();

            $processIcon->setLocales($request->input("locales"));

            $processIcon_id = $processIcon->id;
        });

        return $this->dataResponse(['processIcon_id' => $processIcon_id], 201);
    }


    public function update(Request $request, $id)
    {

        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {

            $processIcon = ProcessIcon::findOrFail($id);

            $processIcon->fill($request->only([
                'icon_uuid',
                'image_uuid'
            ]));

            $processIcon->save();

            $processIcon->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }

    public function destroy($id)
    {

        DB::transaction(function () use ($id) {
            $process_icon = ProcessIcon::findOrFail($id);

            $process_icon->locales()->delete();

            $process_icon->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }

    public function show($id)
    {
        $processIcon = ProcessIcon::with('icon', 'image', 'locales')->where('id', $id)->first();
        return $this->dataResponse($processIcon);
    }


    private function getValidationRules(): array
    {
        return [
            'image_uuid' => 'required|exists:files,id',
            'icon_uuid' => 'required|exists:files,id',
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'image_uuid.required' => 'İmage id mütləqdir',
            'image_uuid.exists' => 'İmage id mövcud deyil',
            'icon_uuid.required' => 'İcon id mütləqdir',
            'icon_uuid.exists' => 'İcon id mövcud deyil',
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
