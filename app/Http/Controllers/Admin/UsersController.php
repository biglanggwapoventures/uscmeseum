<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname'      => 'required',
            'lastname'       => 'required',
            'email'          => 'required|email|unique:users',
            'contact_number' => 'required'
        ]);

        // set default password
        $data['password'] = bcrypt(User::DEFAULT_PASSWORD);

        User::create($data);

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User                $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'firstname'      => 'required',
            'lastname'       => 'required',
            'email'          => "required|email|unique:users,email,{$user->id}",
            'contact_number' => 'required',
            'password'       => 'sometimes|nullable|confirmed',
        ]);

        if (isset($data['password']) && strlen($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['enabled']);

        $user->fill($data)->save();

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect('admin/users')->with('deletion', ['variant' => 'success', 'message' => "You have successfully deleted user: {$user->email}"]);
        }catch (\Exception $exception){
            return redirect('admin/users')->with('deletion', ['variant' => 'danger', 'message' => "Cannot delete \"{$user->email}\" because it is being used."]);
        }
    }
}
