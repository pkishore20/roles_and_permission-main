<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:role_show',only: ['index']),
            new Middleware('permission:role_edit',only: ['edit']),
            new Middleware('permission:role_create',only: ['create']),
            new Middleware('permission:role_delete',only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        // dd($roles);
        return view('role.list',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('role.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role' => 'required|unique:roles,name|min:3',
                'permissions' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $role = Role::create(['name' => $request->role]);
            if(!empty($request->permissions))
            {
                foreach ($request->permissions as $name)
                {
                    $role->givePermissionTo($name);
                }
            }

            return response()->json(['message' => 'Role Created successfully!'], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        $roles = Role::findById($id);
        $permissions = Permission::orderBy('name', 'ASC')->get();
        $hasPermissions = $roles->permissions->pluck('name');
        return view('role.edit',compact('roles','permissions','hasPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'role' => 'required|unique:roles,name,' . $request->id
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $role = Role::findById($request->id);
            if (!$role) {
                return response()->json(['error' => 'Role not found.'], 404);
            }

            $role->name = $request->role;
            $role->save();

            // Sync permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            return response()->json(['message' => 'Role updated successfully!'], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
