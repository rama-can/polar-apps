<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('permission:read roles');
        $this->middleware('permission:create roles')->only(['create', 'store']);
        $this->middleware('permission:update roles')->only(['edit', 'update']);
        $this->middleware('permission:delete roles')->only(['destroy']);
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $title = 'Role';
        if ($request->ajax()) {
            return $this->roleService->dataTable();
        }

        return view('admin.configuration.role', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = new Role();

        return view('admin.configuration.role-form', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $result = $this->roleService->store($request->all());
        return response()->json($result);
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
    public function edit(Role $role)
    {
        return view('admin.configuration.role-form', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $requestData = $request->only(['name', 'guard_name']);
        $response = $this->roleService->update($id, $requestData);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $result = $this->roleService->destroy($role);
        return response()->json($result);
    }
}
