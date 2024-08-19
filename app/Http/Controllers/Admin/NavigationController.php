<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Navigation;
use Illuminate\Http\Request;
use App\Services\NavigationService;
use App\Http\Requests\NavigationRequest;
use App\Http\Controllers\Controller;

class NavigationController extends Controller
{
    protected $navigationService;
    public function __construct(NavigationService $navigationService)
    {
        $this->middleware('permission:read configurations');
        $this->middleware('permission:create configurations')->only(['create', 'store']);
        $this->middleware('permission:update configurations')->only(['edit', 'update']);
        $this->middleware('permission:delete configurations')->only(['destroy']);
        $this->navigationService = $navigationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Navigation';
        if ($request->ajax()) {
            return $this->navigationService->dataTable();
        }

        return view('admin.configuration.navigation', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $navigation = new Navigation();
        $parent = Navigation::where('type_menu', '=', 'parent')->get();
        $role = Role::all();

        return view('admin.configuration.navigation-form', compact('navigation', 'parent', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NavigationRequest $request)
    {
        $result = $this->navigationService->store($request->all());

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
    public function edit(Navigation $navigation)
    {
        $role = Role::all();
        $parent = Navigation::where('type_menu', '=', 'parent')->get();

        return view('admin.configuration.navigation-form', compact('navigation', 'parent', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NavigationRequest $request, string $id)
    {
        $requestData = $request->all();
        $response = $this->navigationService->update($id, $requestData);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Navigation $navigation)
    {
        $result = $this->navigationService->destroy($navigation);
        return response()->json($result);
    }
}
