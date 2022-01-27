<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    use ApiResponder;

    public function index()
    {
        $contacts = Contact::where('id', 1)->get();
        return $this->dataResponse($contacts);
    }


    public function show($id)
    {
        $contacts = Contact::findOrFail($id);
        return $this->dataResponse($contacts);
    }


    public function store(Request $request)
    {
        $this->validate($request, $this->getValidationRules());

        $contact = new Contact;
        $contact->address = $request->address;
        $contact->tel = $request->tel;
        $contact->fax = $request->fax;
        $contact->lng = $request->lng;
        $contact->lat = $request->lat;
        $contact->email = $request->email;
        $contact->created_at = now();
        $contact->save();

        return $this->successResponse(trans('responses.ok'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, $this->getValidationRules());

        $contact = contact::findOrFail($id);
        $contact->address = $request->address;
        $contact->tel = $request->tel;
        $contact->fax = $request->fax;
        $contact->lng = $request->lng;
        $contact->lat = $request->lat;
        $contact->email = $request->email;
        $contact->updated_at = now();
        $contact->save();

        return $this->successResponse(trans('responses.ok'));
    }


    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return $this->successResponse(trans("responses.ok"));
    }


    private function getValidationRules(): array
    {
        return [
            'address' => 'required',
            'tel' => 'required',
            'fax' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'email' => 'required|email',
        ];
    }
}
