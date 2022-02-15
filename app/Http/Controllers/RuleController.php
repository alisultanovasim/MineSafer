<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Traits\ApiResponder;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $rule = Rule::with('locales');
        } else {
            $rule = Rule::with('locale');
        }
        return $this->dataResponse($rule->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $rule_id = null;
        DB::transaction(function () use ($request, &$rule_id) {
            $rule = new Rule;
            $rule->created_at = now();
            $rule->save();
            $rule->setLocales($request->input("locales"));
            $rule_id = $rule->id;
        });

        return $this->dataResponse(['rule_id' => $rule_id], 201);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $rule = Rule::findOrFail($id);
            $rule->updated_at = now();
            $rule->save();
            $rule->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $rule = Rule::findOrFail($id);
            $rule->locales()->delete();
            $rule->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
