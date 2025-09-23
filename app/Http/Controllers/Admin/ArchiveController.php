<?php

namespace App\Http\Controllers\Admin;

use App\Models\Archive;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArchiveTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ArchiveController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-archive')->only('index');
        $this->middleware('permission:create-archive')->only(['create', 'store']);
        $this->middleware('permission:edit-archive')->only(['edit', 'update']);
        $this->middleware('permission:delete-archive')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Archive::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($link) {
                    return $link->created_at->format('d M Y'); 
                })

                ->addColumn('bn_title', function ($archive) {
                    return optional(ArchiveTranslation::where('archive_id', $archive->id)->where('locale', 'bn')->first())->title ?? '';
                })

                ->addColumn('en_title', function ($archive) {
                    return optional(ArchiveTranslation::where('archive_id', $archive->id)->where('locale', 'en')->first())->title ?? '';
                })

                ->filterColumn('bn_title', function($query, $keyword) {
                    $query->whereHas('translations', function($q) use ($keyword) {
                        $q->where('locale', 'bn')->where('title', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('en_title', function($query, $keyword) {
                    $query->whereHas('translations', function($q) use ($keyword) {
                        $q->where('locale', 'en')->where('title', 'like', "%{$keyword}%");
                    });
                })


                ->addColumn('action', function (Archive $archive) {
                    $editUrl = route('archive.edit', $archive->id);
                    $deleteUrl = route('archive.destroy', $archive->id);

                    $buttons = '';
                    if (auth()->user()->can('edit-archive')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }
                    if (auth()->user()->can('delete-archive')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $archive->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }
                    return $buttons ?: '<span class="text-muted">No Actions</span>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.archive.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.archive.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|min:6',
            'description'     => 'required|min:6',
            'category_id'     => 'required|exists:categories,id',
            'docs'            => 'nullable|mimes:pdf,docx,doc|max:10240'
        ]);

        $filename = 'none';
        if ($request->hasFile('docs')) {
            $filename = $request->file('docs')->store('archive/files', 'public');
        }

        $archive = Archive::create([
            'category_id'   => $validated['category_id'],
            'slug'          => Str::slug($request->title),
            'docs'          => $filename,
            'published_by'  => auth()->id(),
        ]);

        ArchiveTranslation::insert([
            [
                'archive_id'   => $archive->id,
                'title'        =>  $request['title'],
                'description'  =>  $request['description'],
                'locale'       => 'bn'
            ],
            [
                'archive_id'   => $archive->id,
                'title'        =>  $request['en_title'],
                'description'  =>  $request['en_description'],
                'locale'       => 'en'
            ],
        ]);

        return redirect()->route('archive.index')->with('success', 'Archive published successfully.');
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Archive $archive)
    {
        $categories = Category::all();
        return view('backend.archive.edit', compact('archive', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Archive $archive)
    {
        $validated = $request->validate([
            'title'           => 'required|min:6',
            'description'     => 'required|min:6',
            'category_id'     => 'required|exists:categories,id',
            'docs'            => 'nullable|mimes:pdf,docx,doc|max:10240'
        ]);

        $bn_archive = $archive->translations()->where('locale', 'bn')->first();
        $en_archive = $archive->translations()->where('locale', 'en')->first();

        if ($request->hasFile('docs')) {
            if ($archive->docs && Storage::exists($archive->docs)) {
                Storage::delete($archive->docs);
            }

            $archive->docs = $request->file('docs')->store('archive/files', 'public');
        }

        $archive->update([
            'category_id'  => $validated['category_id'],
            'slug'         => Str::slug($request->en_title),
            'published_by' => auth()->id(),
            'docs'         => $archive->docs,
        ]);

        $bn_archive->update([
            'title' => $request['title'],
            'description' => $request['description'],
        ]);

        $en_archive->update([
            'title' => $request['en_title'],
            'description' => $request['en_description'],
        ]);

        return redirect()->route('archive.index')->with('success', 'Archive updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Archive $archive)
    {
        if ($archive->docs && Storage::disk('public')->exists($archive->docs)) {
            Storage::disk('public')->delete($archive->docs);
        }

        $archive->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Archive deleted successfully!'
        ]);
    }
}
