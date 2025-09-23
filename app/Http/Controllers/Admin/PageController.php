<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PageTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-page')->only('index');
        $this->middleware('permission:create-page')->only(['create', 'store']);
        $this->middleware('permission:edit-page')->only(['edit', 'update']);
        $this->middleware('permission:delete-page')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Page::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bn_title', function ($page) {
                    return optional(PageTranslation::where('page_id', $page->id)->where('locale', 'bn')->first())->title ?? '';
                })

                ->addColumn('en_title', function ($page) {
                    return optional(PageTranslation::where('page_id', $page->id)->where('locale', 'en')->first())->title ?? '';
                })

                ->addColumn('url', function ($page) {
                    return '/page/' . $page->slug;
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

                ->filterColumn('url', function($query, $keyword) {
                    $query->where('slug', 'like', "%{$keyword}%");
                })

                ->addColumn('action', function (Page $page) {
                    $editUrl = route('page.edit', $page->id);
                    $deleteUrl = route('page.destroy', $page->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-page')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('delete-page')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $page->id . '" style="display:inline-block;">
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


        return view('backend.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5'
        ]);

        $page = $page->create([
            'slug' =>  Str::slug($request->en_title),
            'published_by' => auth()->user()->id
        ]);

        PageTranslation::create([
            'page_id'       =>  $page->id,
            'locale'        =>  'bn',
            'title'         =>  $request['title'],
            'description'   =>  $request['description']
        ]);

        PageTranslation::create([
            'page_id'       =>  $page->id,
            'locale'        =>  'en',
            'title'         =>  $request['en_title'],
            'description'   =>  $request['en_description']
        ]);

        session()->flash('success', 'Page created successfully!');
        return redirect()->route('page.index');
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
    public function edit(Page $page)
    {
        return view('backend.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:5'
        ]);

        $en_page = PageTranslation::where('page_id', $page->id)->where('locale', 'en')->first();
        $bn_page = PageTranslation::where('page_id', $page->id)->where('locale', 'bn')->first();

        $page->update([
            'slug' =>  Str::slug($request->en_title),
            'published_by' => auth()->user()->id
        ]);

        $bn_page->update([
            'title'         =>  $request['title'],
            'description'   =>  $request['description']
        ]);

        $en_page->update([
            'title'         =>  $request['en_title'],
            'description'   =>  $request['en_description']
        ]);

        session()->flash('success', 'Page updated successfully!');
        return redirect()->route('page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $menu = $page->menu_name();

        if (count($menu)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This page is associated with menus, delete them first!'
            ], 400); 
        }

        $page->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Page deleted successfully!'
        ]);
    }
 
}
