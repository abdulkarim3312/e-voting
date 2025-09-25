<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RoleController extends Controller
{

    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-roles')->only('index');
        $this->middleware('permission:create-roles')->only(['create', 'store']);
        $this->middleware('permission:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission:delete-roles')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions')->orderBy('name', 'asc');

            return DataTables::of($roles)
                ->addColumn('permissions', function ($row) {
                    $chunks = $row->permissions->chunk(ceil($row->permissions->count() / 4));
                    $html = '<div class="row">';
                    foreach ($chunks as $chunk) {
                        $html .= '<div class="col-md-3"><ul>';
                        foreach ($chunk as $permission) {
                            $html .= '<li class="text-capitalize">' . e($permission->name) . '</li>';
                        }
                        $html .= '</ul></div>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $buttons  = '<a href="' . route('roles.edit', $row->id) . '" ';
                    $buttons .= 'class="btn btn-sm btn-primary mx-1"><i class="fa fa-edit"></i></a> ';

                    $buttons .= '<form action="' . route('roles.destroy', $row->id) . '" method="POST" class="delete-form d-inline" data-id="' . $row->id . '">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>';

                    return $buttons;
                })
                ->rawColumns(['permissions', 'action']) 
                ->make(true);
        }

        return view('backend.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('backend.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $validData = $request->validated();

        DB::beginTransaction();
        try{

            $role = Role::create($request->except('permissions'));

            $permissions = $request['permissions'];

            if (isset($permissions)){
                $role->syncPermissions($permissions);
            } else {
                $permissions = [];
                $role->syncPermissions($permissions);
            }

            DB::commit();

            session()->flash('success', 'Role created successfully !!');
            return redirect()->route('roles.index');

        } catch (\Exception $exception){
            DB::rollBack();
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
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
    public function edit(Role $role)
    {
        $permissionNames = $role->permissions->pluck('name')->toArray();

        $permissions = Permission::select('id', 'name', 'module')
            ->orderBy('module')
            ->get()
            ->groupBy('module'); 

        return view('backend.roles.edit', compact('role', 'permissions', 'permissionNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3|unique:roles,name,'.$id.',id'
        ]);

        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            session()->flash('success', 'Role Updated successfully !!');
            return redirect()->back();
            // return redirect()->route('roles.index');
        } else {
            return redirect()->route('roles.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if ($role == null) {
            session()->flash('error', 'Role not found!!');
            return redirect()->route('permissions.index');
        }
        $role->delete();
        session()->flash('success', 'Role Deleted successfully !!');
        return redirect()->route('roles.index');
    }
}
