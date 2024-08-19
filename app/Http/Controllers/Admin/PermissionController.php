<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->middleware('permission:read permissions');
        $this->middleware('permission:create permissions')->only(['create', 'store']);
        $this->middleware('permission:update permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete permissions')->only(['destroy']);
        $this->permissionService = $permissionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Permission';
        if ($request->ajax()) {
            return $this->permissionService->dataTable();
        }

        return view('admin.configuration.permission', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $result = $this->permissionService->createPermission($request->all());

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($roleId = null)
    {
        $permissions = $this->permissionService->getRolePermission($roleId);

        return view('admin.configuration.permission-form', compact('permissions', 'roleId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        $role = Role::findOrFail($request->input('role_id'));

        $result = $this->permissionService->destroy($permission, $role);
        return response()->json($result);
    }
}
