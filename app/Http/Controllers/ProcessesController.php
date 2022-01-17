<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Paginatable;

class ProcessesController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $processes = Process::query()->with('image', 'locales','categories');
        } else {
            $processes = Process::query()->with('image', 'locale','category');
        }
        return $this->dataResponse($processes->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {

        if (auth()->check()) {
            $process = Process::with('image', 'locales')->findOrFail($id);
        } else {
            $process = Process::with('image', 'locale')->findOrFail($id);
        }
        return $this->dataResponse($process);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $process_id = null;

        DB::transaction(function () use ($request, &$process_id) {
            $process = new Process();
            $process->fill($request->only([
                'image_uuid',
                'processes_category_id'
            ]));
            $process->save();

            $process->setLocales($request->input("locales"));

            $process_id = $process->id;
        });

        return $this->dataResponse(['process_id' => $process_id], 201);
    }


    public function update(Request $request, $id)
    {

        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $process = Process::findOrFail($id);
            $process->fill($request->only([
                'image_uuid',
                'processes_category_id'
            ]));

            $process->save();

            $process->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }

    public function destroy($id)
    {

        DB::transaction(function () use ($id) {
            $process = Process::findOrFail($id);

            $process->locales()->delete();

            $process->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'image_uuid' => 'required|exists:files,id',
            'processes_category_id' => 'required|numeric',
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'processes_category_id.required' => 'Processes category mütləqdir',
            'image_uuid.required' => 'İmage id mütləqdir',
            'image_uuid.exists' => 'İmage id mövcud deyil',
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
