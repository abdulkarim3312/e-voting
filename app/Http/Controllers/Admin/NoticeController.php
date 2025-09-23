<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\NoticeTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NoticeController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-notice')->only(['index', 'show']);
        $this->middleware('permission:create-notice')->only(['create', 'store']);
        $this->middleware('permission:edit-notice')->only(['edit', 'update']);
        $this->middleware('permission:delete-notice')->only('destroy');
    }
    /**
     * This functions is returning all notices
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Notice::with('translations', 'user')->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bn_title', function ($category) {
                    return optional(NoticeTranslation::where('notice_id', $category->id)->where('locale', 'bn')->first())->title ?? '';
                })

                ->addColumn('en_title', function ($category) {
                    return optional(NoticeTranslation::where('notice_id', $category->id)->where('locale', 'en')->first())->title ?? '';
                })

                ->addColumn('published_by', function ($notice) {
                    return optional($notice->user)->name ?? 'Unknown';
                })

                ->addColumn('created_at', function ($notice) {
                    return $notice->created_at->format('d-m-Y');
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

                ->addColumn('action', function (Notice $notice) {
                    $dropdown = '
                        <div class="dropdown">
                            <button class="btn btn-sm btn-purple dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">';

                    if (auth()->user()->can('view-notice')) {
                        $dropdown .= '
                            <li>
                                <a class="dropdown-item" href="' . route('notice.show', $notice->id) . '">
                                    <i class="fa fa-eye text-primary"></i> View
                                </a>
                            </li>';
                    }
                    if (auth()->user()->can('edit-notice')) {
                        $dropdown .= '
                            <li>
                                <a class="dropdown-item" href="' . route('notice.edit', $notice->id) . '">
                                    <i class="fa fa-edit text-warning"></i> Edit
                                </a>
                            </li>';
                    }
                    if (auth()->user()->can('delete-notice')) {
                        $dropdown .= '
                            <li>
                                <form action="' . route('notice.destroy', $notice->id) . '" method="POST" class="delete-form" data-id="' . $notice->id . '" data-name="' . e($notice->title ?? $notice->id) . '">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </li>';
                    }

                    $dropdown .= '
                            </ul>
                        </div>';

                    return $dropdown;
                })

                ->rawColumns(['action'])
                ->make(true);
        }


        return view('backend.notice.index');
    }
    
    private function make_slug($string)
    {
        return preg_replace('/\s+/u', '-', trim($string));
    }
    /**
     * Showing the form for creating a new notice.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.notice.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'category_id' => 'required|integer',
        ]);

        $filename = 'none';
        if ($request->hasFile('docs')) {
            $filename = $request->file('docs')->store('files');
        }

        $ntitle = $request->input('en_title') ?: $request->input('title');
        $slug = $this->make_slug(Str::limit($ntitle, 60)) . rand(190005, 8237476193);

        $notice = Notice::create([
            'category_id'   => $request->input('category_id'),
            'slug'          => $slug,
            'docs'          => $filename,
            'promote'       => $request->input('promote', 0),
            'created_at'    => $request->input('noticeDate', now()),
            'published_by'  => auth()->id(),
        ]);

        NoticeTranslation::create([
            'notice_id'   => $notice->id,
            'title'       => $request->input('title'),
            'description' => $request->input('description', ''),
            'locale'      => 'bn',
        ]);

        NoticeTranslation::create([
            'notice_id'   => $notice->id,
            'title'       => $request->input('en_title', $request->input('title')),
            'description' => $request->input('en_description', ''),
            'locale'      => 'en',
        ]);

        session()->flash('success', 'Notice published successfully');
        return redirect()->route('notice.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        return view('backend.notice.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     * @param  \App\Notice  $notice
     * @return edit view
     */
    public function edit(Notice $notice)
    {
        $categories = Category::all();
        return view('backend.notice.edit', compact('notice', 'categories'));
    }

    /**
     * Update the specified notice in database.
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Notice  $notice
     * @return back
     */
    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title'       => 'required',
            'category_id' => 'required|integer',
        ]);

        $en_notice = NoticeTranslation::where('notice_id', $notice->id)->where('locale', 'en')->first();
        $bn_notice = NoticeTranslation::where('notice_id', $notice->id)->where('locale', 'bn')->first();

        $ntitle = $request->input('en_title') ?: $request->input('title');
        $slug = $this->make_slug(Str::limit($ntitle, 60)) . rand(190005, 8237476193);

        if ($request->hasFile('docs')) {
            if ($notice->docs && Storage::exists($notice->docs)) {
                Storage::delete($notice->docs);
            }

            $filename = $request->file('docs')->store('files');
            $notice->docs = $filename;
        }

        $notice->update([
            'category_id'  => $request->input('category_id'),
            'slug'         => $slug,
            'promote'      => $request->input('promote', 0),
            'published_by' => auth()->id(),
            'created_at'   => $request->input('noticeDate', $notice->created_at),
            'docs'         => $notice->docs, 
        ]);

        if ($bn_notice) {
            $bn_notice->update([
                'title'       => $request->input('title'),
                'description' => $request->input('description', ''),
            ]);
        }

        if ($en_notice) {
            $en_notice->update([
                'title'       => $request->input('en_title', $request->input('title')),
                'description' => $request->input('en_description', ''),
            ]);
        }

        session()->flash('success', 'Notice has been updated successfully');
        return redirect()->route('notice.index');
    }

    /**
     * Remove the specified notice from database.
     * @param  \App\Notice  $notice
     * @return Notice::all() back to all notices page
     */
    public function destroy(Notice $notice)
    {
        try {
            if ($notice->docs && Storage::exists($notice->docs)) {
                Storage::delete($notice->docs);
            }
            $notice->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'The notice and attached file have been deleted successfully!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the notice.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function single( $slug )
    {
        $notice = Notice::where('slug', $slug)->first();
        return view('front.notice.single', compact('notice'));
    }
}
