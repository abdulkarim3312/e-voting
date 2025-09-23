<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;



class UserController extends Controller
{

    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-users')->only('index');
        $this->middleware('permission:create-users')->only(['create', 'store']);
        $this->middleware('permission:edit-users')->only(['edit', 'update']);
        $this->middleware('permission:delete-users')->only('destroy');
    }
    /**
     * admin login
     */
    public function adminLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard'); 
        }
        return view('backend.auth.login');
    }
    /**
     * admin login post
     */
    public function adminLoginPost(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email!',
            ]);
        }

        $user->last_login = Carbon::now();
        $user->save();

        $roles = $user->getRoleNames();

        LoginHistory::create([
            'user_type' => $roles[0] ?? null,
            'user_id' => $user->id,
            'user_ip' => $request->ip()
        ]);

        $user->user_type = $roles[0] ?? null;
        
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
    /**
     * admin logout
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::query();

            return DataTables::of($users)
                ->addColumn('photo', function ($row) {
                    $imageUrl = $row->photo ? asset($row->photo) : '';
                    return '<img src="' . $imageUrl . '" width="40">';
                })
                ->addColumn('role', function ($row) {
                    return $row->role->name ?? '';
                })
                ->addColumn('last_login', function ($row) {
                    if ($row->last_login) {
                        return $row->last_login->timezone('Asia/Dhaka')->format('d M Y, h:i A');
                    }
                    return '';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    return '<input type="checkbox" class="status-toggle big-checkbox" data-id="' . $row->id . '" ' . $checked . '>';
                })
                ->addColumn('action', function ($row) {
                    $edit = '<button type="button" class="btn btn-sm btn-primary text-white editBtn mx-1" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button>';

                    $delete = '<form action="' . route('users.destroy', $row->id) . '" method="POST" class="delete-form d-inline" data-id="' . $row->id . '" data-name="' . $row->name . '">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' .
                        '</form>';

                    return $edit . $delete;
                })
                ->rawColumns(['photo','status', 'action']) 
                ->make(true);
        }
        $roles = Role::select('id', 'name')->get();
        return view('backend.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required',
        ]);

        DB::beginTransaction();
    
        try {

            $imagePath = null;

            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/profile_images'), $imageName);
                $imagePath = 'uploads/profile_images/' . $imageName;
            }

            $role = Role::find($request->role_id);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->status = $request->status;
            $user->email_verified_at = \Carbon\Carbon::now();
            $user->role_id = $request->role_id;
            $user->photo = $imagePath;
            $user->save();
            $user->assignRole($role);

            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'created successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:6', 
        ]);

        DB::beginTransaction();
    
        try {

            $user = User::Find($id);

            $imagePath = $user->photo;

            if ($request->hasFile('photo')) {
                if ($imagePath && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image = $request->file('photo');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/profile_images'), $imageName);
                $imagePath = 'uploads/profile_images/' . $imageName;
            }

            $role = Role::findOrFail($request->role_id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password ? bcrypt($request->password) : $user->password;
            $user->status = $request->status;
            $user->role_id = $request->role_id;
            $user->photo = $imagePath;
            $user->save();
            $user->syncRoles($role);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'User deleted successfully !!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete the user.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'Status updated successfully']);
    }
}
