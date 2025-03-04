<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;
class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user_show',only: ['index']),
            new Middleware('permission:user_edit',only: ['edit']),
            new Middleware('permission:user_create',only: ['create']),
            new Middleware('permission:user_delete',only: ['destroy']),
        ];
    }


    public function index()
    {
        $users = User::all();
        return view('user.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        $user->syncRoles($request->role);
        return response()->json(['message' => 'User Created successfully!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $user = User::find($id);
        return view('user.edit',compact('roles','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->toArray());
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|unique:users,name,' . $request->id,
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();
        $user->syncRoles($request->role);
        return response()->json(['message' => 'User Updated successfully!'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
