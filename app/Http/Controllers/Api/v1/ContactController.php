<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = \Auth::user();
        if (request()->q) {
            $contact = Contact::where(function ($query) {
                    return $query->where(\DB::raw("LOWER(name)"), 'LIKE', "%".strtolower(request()->input('q'))."%")
                        ->orWhere(\DB::raw("LOWER(email)"), 'LIKE', "%".strtolower(request()->input('q'))."%")
                        ->orWhere(\DB::raw("LOWER(name)"), 'LIKE', "%".strtolower(request()->input('q'))."%");;
                })
                ->where('user_id', $user->id)
                ->get();
        } else {
            $contact = Contact::where('user_id', $user->id)->get();
        }

        return $contact;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_id' => $user->id
        ]);

        return $contact;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = \Auth::user();
        $contact = $user->contacts->where('id', $id)->first();
        if (!$contact) {
            return response()->json(['message' => 'data not found'], 404);
        }

        return $contact;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \Auth::user();

        $contact = $user->contacts->where('id', $id)->firstOrFail();

        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return $contact;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \Auth::user();
        return $contact = $user->contacts->where('id', $id)->firstOrFail()->delete();
    }
}
