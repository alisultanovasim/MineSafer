<?php

namespace App\Http\Controllers;

use App\Models\PublicCouncil;
use App\Models\PublicCouncilLocale;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicCouncilController extends Controller
{
    use ApiResponder;

    public function index()
    {
        if (auth()->check()) {
            $publicCouncil = PublicCouncil::with('locales');
        } else {
            $publicCouncil = PublicCouncil::with('locale');
        }

        return $this->dataResponse($publicCouncil->limit(1)->orderByDesc('id')->first());
    }


    public function show($id)
    {
        if (auth()->check()) {
            $publicCouncil = PublicCouncil::with('locales')->findOrFail($id);
        } else {
            $publicCouncil = PublicCouncil::with('locale')->findOrFail($id);
        }
        return $this->dataResponse($publicCouncil);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());
        PublicCouncil::query()->delete();
        PublicCouncilLocale::query()->delete();
        DB::transaction(function () use ($request) {
            $publicCouncil = new PublicCouncil();
            $publicCouncil->created_at = now();
            $publicCouncil->save();
            $publicCouncil->setLocales($request->locales);
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules($id), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $publicCouncil = PublicCouncil::findOrFail($id);
            $publicCouncil->updated_at = now();
            $publicCouncil->save();
            $publicCouncil->setLocales($request->locales);
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $publicCouncil = PublicCouncil::findOrFail($id);
            $publicCouncil->locales()->delete();
            $publicCouncil->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules($id = null): array
    {
        return [
            'locales.*.local' => 'required',
            'locales.*.text' => 'required'
        ];
    }

    public function customAttributes(): array
    {
        return [
            'locales.*.text.required' => 'Mətn mütləqdir'
        ];
    }
}
