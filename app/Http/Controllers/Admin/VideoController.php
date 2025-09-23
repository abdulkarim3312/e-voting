<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VideoController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-video')->only('index');
        $this->middleware('permission:create-video')->only(['create', 'store']);
        $this->middleware('permission:edit-video')->only(['edit', 'update']);
        $this->middleware('permission:delete-video')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Video::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($banner) {
                    return $banner->created_at->format('d M Y'); 
                })
                ->addColumn('action', function (Video $video) {
                    $editUrl = route('video.edit', $video->id);
                    $deleteUrl = route('video.destroy', $video->id);

                    $buttons = '';

                    if (auth()->user()->can('edit-video')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('delete-video')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $video->id . '" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        ';
                    }
                    return $buttons ?: '<span class="text-muted">No Actions</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.video.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		return view('backend.video.create');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
	{
		$request->validate([
			'link'      =>  'required'
		]);

		Video::create([
			'title'         =>  $request['title'],
			'description'   =>  $request['description'],
			'link'          =>  $request['link'],
		]);

		session()->flash('success', 'Video added successfully!');
		return redirect()->route('video.index');
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        return view('backend.video.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
	{
		$request->validate([
			'link'      =>  'required'
		]);

		$video->update([
			'title'         =>  $request['title'],
			'description'   =>  $request['description'],
			'link'          =>  $request['link'],
		]);

		session()->flash('success', 'Video updated successfully!');
		return redirect()->route('video.index');
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
	{
		$video->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Video deleted successfully!'
        ]);
	}
}
