<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-banner')->only('index');
        $this->middleware('permission:create-banner')->only(['create', 'store']);
        $this->middleware('permission:edit-banner')->only(['edit', 'update']);
        $this->middleware('permission:delete-banner')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Banner::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('image', function ($banner) {
                    $url = asset('storage/' . $banner->image);
                    return '<img src="' . $url . '" width="40">';
                })

                ->addColumn('title', function ($banner) {
                    return e($banner->title);
                })

                ->addColumn('created_at', function ($banner) {
                    return $banner->created_at->format('d M Y'); 
                })

                ->addColumn('action', function ($banner) {
                    $editUrl = route('banner.edit', $banner->id);
                    $deleteUrl = route('banner.destroy', $banner->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-banner')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }
                    
                    if (auth()->user()->can('delete-banner')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $banner->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }

                    return $buttons ?: '<span class="text-muted">No Actions</span>';
                })

                ->rawColumns(['image', 'action']) 
                ->make(true);
        }
        return view('backend.banner.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('banner_image', 'public'); 
        }

        Banner::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $filename,
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner has been added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('backend.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $data['image'] = $request->file('image')->store('banner_image', 'public');
        }

        $banner->update($data);

        return redirect()->route('banner.index')->with('success', 'Banner has been updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return response()->json([
            'status' => true,
            'message' => 'The banner has been deleted successfully!',
        ]);
    }
}
