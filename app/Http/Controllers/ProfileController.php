<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $input = $request->validate([
            'firstname'      => ['required', 'string', 'max:255'],
            'lastname'       => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string'],
            'password'       => ['sometimes', 'nullable', 'string', 'min:4', 'confirmed'],
        ]);

        if($request->has('password') && strlen($request->input('password'))){
            $input['password'] = bcrypt($request->input('password'));
        }else{
            unset($input['password']);
        }

        Auth::user()->update($input);

        return redirect()->back()->with('message', 'Profile successfully updated!');
    }
}
