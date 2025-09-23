<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{

    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-category')->only('index');
        $this->middleware('permission:create-category')->only(['create', 'store']);
        $this->middleware('permission:edit-category')->only(['edit', 'update']);
        $this->middleware('permission:delete-category')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bn_title', function ($category) {
                    return optional(CategoryTranslation::where('category_id', $category->id)->where('locale', 'bn')->first())->title ?? '';
                })

                ->addColumn('en_title', function ($category) {
                    return optional(CategoryTranslation::where('category_id', $category->id)->where('locale', 'en')->first())->title ?? '';
                })

                ->addColumn('url', function ($category) {
                    return '/category/' . $category->slug;
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

                ->addColumn('action', function (Category $category) {
                    $editUrl = route('category.edit', $category->id);
                    $deleteUrl = route('category.destroy', $category->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-category')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('delete-category')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $category->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }

                    return $buttons;
                })

                ->rawColumns(['action'])
                ->make(true);
        }


        return view('backend.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:4',
        ]);

        $category = Category::create([
            'slug' => Str::slug($request->en_title),
        ]);

        CategoryTranslation::create([
            'category_id' => $category->id,
            'title' => $request->title,
            'description' => $request->description,
            'locale' => 'bn',
        ]);

        CategoryTranslation::create([
            'category_id' => $category->id,
            'title' => $request->en_title,
            'description' => $request->en_description,
            'locale' => 'en',
        ]);

        session()->flash('success', 'Category has been created successfully');
        return redirect()->route('category.index');

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
    public function edit(Category $category)
    {
        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:4',
        ]);

        $category = Category::findOrFail($id);

        $en_category = CategoryTranslation::where('category_id', $category->id)
            ->where('locale', 'en')
            ->first();

        $bn_category = CategoryTranslation::where('category_id', $category->id)
            ->where('locale', 'bn')
            ->first();

        $category->update([
            'slug' => Str::slug($request->en_title),
        ]);

        if ($bn_category) {
            $bn_category->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        }

        if ($en_category) {
            $en_category->update([
                'title' => $request->en_title,
                'description' => $request->en_description,
            ]);
        }

        session()->flash('success', 'category updated successfully');
        return redirect()->route('category.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
	{
		$category = Category::findOrFail($id);
		$category->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully!']);
	}
}
