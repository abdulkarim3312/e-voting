<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MenuTranslation;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-menu')->only('index');
        $this->middleware('permission:create-menu')->only(['create', 'store']);
        $this->middleware('permission:edit-menu')->only(['edit', 'update']);
        $this->middleware('permission:delete-menu')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::with(['parent'])->select('menus.*');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bn_title', function ($menu) {
                    return optional(MenuTranslation::where('menu_id', $menu->id)->where('locale', 'bn')->first())->title ?? '';
                })

                ->addColumn('en_title', function ($menu) {
                    return optional(MenuTranslation::where('menu_id', $menu->id)->where('locale', 'en')->first())->title ?? '';
                })

                ->addColumn('status', function ($menu) {
                    return $menu->is_active ? 'Active' : 'Hidden';
                })

                ->addColumn('page_name', function ($menu) {
                    return '<a href="#">' . ($menu->page_name() ?? '-') . '</a>';
                })

                ->addColumn('menu_parent', function ($menu) {
                    return $menu->menu_parent() ?? 'No';
                })

                ->addColumn('position', function ($menu) {
                    return match ($menu->position) {
                        1 => 'Top',
                        2 => 'Navigation',
                        3 => 'Side Nav',
                        4 => 'Footer',
                        5 => 'Top->About Us',
                        default => '-',
                    };
                })
                ->filterColumn('bn_title', function($query, $keyword) {
                    $query->whereHas('title', function($q) use ($keyword) {
                        $q->where('locale', 'bn')->where('title', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('en_title', function($query, $keyword) {
                    $query->whereHas('title', function($q) use ($keyword) {
                        $q->where('locale', 'en')->where('title', 'like', "%{$keyword}%");
                    });
                })

                ->addColumn('action', function ($menu) {
                    $editUrl = route('menu.edit', $menu->id);
                    $deleteUrl = route('menu.destroy', $menu->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-menu')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('delete-menu')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $menu->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }

                    return $buttons ?: '<span class="text-muted">No Actions</span>';
                })

                ->rawColumns(['page_name', 'action'])
                ->make(true);
        }


        return view('backend.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allPages = Page::all();
        $menus = Menu::all();
        $categories = Category::all();
        return view('backend.menu.create', compact('allPages', 'menus', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         =>  'required|min:4',
            'position'      =>  'required'
        ]);

        if($request['page_id'] ){
            $menu = Menu::create([
                'category_id'   =>  $request['category_id'],
                'page_id'       =>  $request['page_id'],
                'slug'          =>  Str::slug($request['title']),
                'custom_link'   =>  $request['custom_link'],
                'parent_id'     =>  $request['parent_id'],
                'position'      =>  $request['position'],
                'published_by'  =>  auth()->user()->id
            ]);

            MenuTranslation::create([
                'menu_id'   =>  $menu->id,
                'locale'    =>  'bn',
                'title'     =>  $request['title']
            ]);

            MenuTranslation::create([
                'menu_id'   =>  $menu->id,
                'locale'    =>  'en',
                'title'     =>  $request['en_title']
            ]);

            session()->flash('success', 'Menu has been created successfully!');
            return redirect()->route('menu.index');
        }

        $menu = Menu::create([
            'category_id'   =>  $request['category_id'],
            'slug'          =>  Str::slug($request['title']),
            'parent_id'     =>  $request['parent_id'],
            'custom_link'   =>  $request['custom_link'],
            'position'      =>  $request['position'],
            'published_by'  =>  auth()->user()->id
        ]);

        MenuTranslation::create([
            'menu_id'   =>  $menu->id,
            'locale'    =>  'bn',
            'title'     =>  $request['title']
        ]);

        MenuTranslation::create([
            'menu_id'   =>  $menu->id,
            'locale'    =>  'en',
            'title'     =>  $request['en_title']
        ]);

        session()->flash('success', 'Menu has been created successfully!');
        return redirect()->route('menu.index');
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
    public function edit(Menu $menu)
    {
        $allPages = Page::all();
        $menus = Menu::all();
        $categories = Category::all();
        return view('backend.menu.edit', compact('menu', 'menus', 'allPages', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'title'         =>  'required|min:4',
            'position'      =>  'required'
        ]);

        $bn_title = MenuTranslation::where('menu_id', $menu->id)->where('locale', 'bn')->first();
        $en_title = MenuTranslation::where('menu_id', $menu->id)->where('locale', 'en')->first();

        if($request['page_id'] ){
            $menu->update([
                'category_id'   =>  $request['category_id'],
                'slug'          =>  Str::slug($request['en_title']),
                'page_id'       =>  $request['page_id'],
                'custom_link'   =>  $request['custom_link'],
                'parent_id'     =>  $request['parent_id'],
                'position'      =>  $request['position'],
                'published_by'  =>  auth()->user()->id,
	            'is_active'     =>  $request['is_active']
            ]);

            if ($bn_title) {
                $bn_title->update([
                    'menu_id' => $menu->id,
                    'title'   => $request['title']
                ]);
            } else {
                MenuTranslation::create([
                    'menu_id' => $menu->id,
                    'locale'  => 'bn',
                    'title'   => $request['title']
                ]);
            }

            if ($en_title) {
                $en_title->update([
                    'title' => $request['en_title']
                ]);
            } else {
                MenuTranslation::create([
                    'menu_id' => $menu->id,
                    'locale'  => 'en',
                    'title'   => $request['en_title']
                ]);
            }

            session()->flash('msg', 'Menu has been updated successfully!');
            return redirect()->route('menu.index');
        }
        $menu->update([
            'category_id'   =>  $request['category_id'],
            'slug'          =>  Str::slug($request['en_title']),
            'parent_id'     =>  $request['parent_id'],
            'page_id'       =>  null,
            'custom_link'   =>  $request['custom_link'],
            'position'      =>  $request['position'],
            'published_by'  =>  auth()->user()->id,
            'is_active'     =>  $request['is_active']
        ]);

        if ($bn_title) {
            $bn_title->update([
                'menu_id' => $menu->id,
                'title'   => $request['title']
            ]);
        } else {
            MenuTranslation::create([
                'menu_id' => $menu->id,
                'locale'  => 'bn',
                'title'   => $request['title']
            ]);
        }

        if ($en_title) {
            $en_title->update([
                'title' => $request['en_title']
            ]);
        } else {
            MenuTranslation::create([
                'menu_id' => $menu->id,
                'locale'  => 'en',
                'title'   => $request['en_title']
            ]);
        }

        session()->flash('success', 'Menu has been updated successfully!');
        return redirect()->route('menu.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $child = $menu->where('parent_id', $menu->id)->get();

        if ($child->count()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This menu has child, delete them first!'
            ], 400); 
        }

        $menu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'The menu has been deleted successfully!'
        ]);
    }
}
