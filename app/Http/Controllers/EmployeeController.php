<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Paginatable;

class EmployeeController extends Controller
{
    use ApiResponder, Paginatable;

    private $perPage;

    public function index()
    {
        if (auth()->check()) {
            $employees = Employee::with('image', 'locales')->orderBy('order', 'asc');
        } else {
            $employees = Employee::with('image', 'locale')->orderBy('order', 'asc');
        }
        return $this->dataResponse($employees->simplePaginate($this->getPerPage()));
    }


    public function show($id)
    {
        if (auth()->check()) {
            $employee = Employee::with('image', 'locales')->findOrFail($id);
        } else {
            $employee = Employee::with('image', 'locale')->findOrFail($id);
        }
        return $this->dataResponse($employee);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules(), $this->customAttributes());

        $employee_id = null;
        DB::transaction(function () use ($request, &$employee_id) {
            $employee = new Employee();
            $employee->fill($request->only([
                'order',
                'image_uuid'
            ]));
            $employee->save();

            $employee->setLocales($request->input("locales"));

            $employee_id = $employee->id;
        });

        return $this->dataResponse(['employee_id' => $employee_id], 201);
    }


    public function update(Request $request, $id)
    {

        $this->validate($request, $this->getValidationRules($id), $this->customAttributes());

        DB::transaction(function () use ($request, $id) {
            $employee = Employee::findOrFail($id);

            $employee->fill($request->only([
                'order',
                'image_uuid'
            ]));
            $employee->save();

            $employee->setLocales($request->input("locales"));
        });

        return $this->successResponse(trans('responses.ok'));
    }

    public function destroy($id)
    {

        DB::transaction(function () use ($id) {
            $employee = Employee::findOrFail($id);

            $employee->locales()->delete();

            $employee->delete();
        });

        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules($id = null): array
    {
        return [
            'image_uuid' => 'required|exists:files,id',
            'order' => 'required|numeric|unique:employees,order,'.$id,
            'locales.*.local' => 'required',
            'locales.*.text' => 'required',
            'locales.*.position_name' => 'required',
        ];
    }

    public function customAttributes(): array
    {
        return [
            'image_uuid.required' => 'İmage id mütləqdir',
            'image_uuid.exists' => 'İmage id mövcud deyil',
            'locales.*.text.required' => 'Mətn mütləqdir',
            'locales.*.position_name.required' => 'Pozisiya mütləqdir',
            'locales.*.local.required' => 'Dil seçimi mütləqdir'
        ];
    }
}
