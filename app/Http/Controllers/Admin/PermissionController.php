<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;


#[Middleware(PermissionMiddleware::class, 'view permissions', only: ['index'])]
#[Middleware(PermissionMiddleware::class, 'create permissions', only: ['create', 'store'])]
#[Middleware(PermissionMiddleware::class, 'edit permissions', only: ['edit', 'update'])]
#[Middleware(PermissionMiddleware::class, 'delete permissions', only: ['destroy'])]

class PermissionController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->get();
        return view('backend.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            Permission::create([
                'name' => $request->name
            ]);

            session()->flash('success', 'Permission created successfully !!');
            return redirect()->route('permissions.index');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
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
        $permission = Permission::find($id);
        return view('backend.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);

        if ($validator->passes()) {
            $permission->update([
                'name' => $request->name
            ]);

            session()->flash('success', 'Permission updated successfully !!');
            return redirect()->route('permissions.index');
        } else {
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        if ($permission == null) {
            session()->flash('error', 'Permission not found!!');
            return redirect()->route('permissions.index');
        }
        $permission->delete();
        session()->flash('success', 'Permission Deleted successfully !!');
        return redirect()->route('permissions.index');
    }
}
