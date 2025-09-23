<?php

namespace App\Http\Controllers\Admin;

use App\Models\Widget;
use Illuminate\Http\Request;
use App\Models\WidgetTranslation;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WidgetController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-widget')->only('index');
        $this->middleware('permission:create-widget')->only(['create', 'store']);
        $this->middleware('permission:edit-widget')->only(['edit', 'update']);
        $this->middleware('permission:delete-widget')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Widget::with('translations')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('bn_title', function ($widget) {
                    return $widget->bn_title();  
                })
                ->addColumn('en_title', function ($widget) {
                    return $widget->en_title();  
                })
                ->addColumn('position_text', function ($widget) {
                    return match($widget->position) {
                        1 => 'Left',
                        2 => 'Right',
                        default => 'Unknown',
                    };
                })
                ->addColumn('promote_text', function ($widget) {
                    return match($widget->promote) {
                        1 => 'Front Page',
                        2 => 'Inner Pages',
                        default => 'All Pages',
                    };
                })
                ->addColumn('action', function (Widget $widget) {
                    $editUrl = route('widget.edit', $widget->id);
                    $deleteUrl = route('widget.destroy', $widget->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-widget')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('delete-widget')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $widget->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }

                    return $buttons ?: '<span class="text-muted">No Actions</span>';
                })
                ->filterColumn('bn_title', function($query, $keyword) {
                    $query->whereHas('translations', function($q) use ($keyword) {
                        $q->where('locale', 'bn')
                        ->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('en_title', function($query, $keyword) {
                    $query->whereHas('translations', function($q) use ($keyword) {
                        $q->where('locale', 'en')
                        ->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.widget.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.widget.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Widget $widget)
    {
        $request->validate([
            'title'       => 'required|string|min:5',
            'position'    => 'required|string',
            'promote'     => 'required|in:1,2,3', 
            'description' => 'nullable|string|min:5',
            'order'       => 'required|integer',
        ]);

        $widget = $widget->create([
            'position'      =>  $request['position'],
            'promote'       =>  $request['promote'],
            'order'         =>  $request['order']
        ]);

        WidgetTranslation::create([
            'widget_id'     =>  $widget->id,
            'locale'        =>  'bn',
            'title'         =>  $request['title'],
            'description'   =>  $request['description']
        ]);

        WidgetTranslation::create([
            'widget_id'     =>  $widget->id,
            'locale'        =>  'en',
            'title'         =>  $request['en_title'],
            'description'   =>  $request['en_description']
        ]);


        session()->flash('success', 'Widget created successfully!');
        return redirect()->route('widget.index');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $widget = Widget::with('translations')->find($id);
        return view('backend.widget.edit', compact('widget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Widget $widget)
    {
        $request->validate([
            'title'         => 'required|string',
            'en_title'      => 'required|string',
            'position'      => 'required|in:1,2',
            'promote'       => 'required|in:1,2,3',
            'description'   => 'nullable|string',
            'order'         => 'required|integer',
        ]);

        $widget->update([
            'position'  => $request['position'],
            'promote'   => $request['promote'],
            'order'     => $request['order'],
        ]);

        $bn_widget = WidgetTranslation::firstOrCreate([
            'widget_id' => $widget->id,
            'locale'    => 'bn',
        ]);

        $en_widget = WidgetTranslation::firstOrCreate([
            'widget_id' => $widget->id,
            'locale'    => 'en',
        ]);

        $bn_widget->update([
            'title'       => $request['title'],
            'description' => $request['description'],
        ]);

        $en_widget->update([
            'title'       => $request['en_title'],
            'description' => $request['en_description'],
        ]);

        session()->flash('success', 'Widget updated successfully!');
        return redirect()->route('widget.index')->withError([
            'msg'   =>  'Something is wrong!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Widget $widget)
    {
        $widget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Widget deleted successfully!'
        ]);
    }
}
