<?php

namespace App\Http\Controllers\Admin;

use App\Models\UsefulLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UsefulLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UsefulLink::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($link) {
                    return $link->created_at->format('d M Y'); 
                })
                ->addColumn('action', function(UsefulLink $link) {
                    $editUrl = route('link.edit', $link->id);
                    $deleteUrl = route('link.destroy', $link->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $link->id . '" style="display:inline-block;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm deleteItem">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.usefulLinks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
	{
		return view('backend.usefulLinks.create');
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
	{
		$request->validate([
			'title'      =>  'required',
			'link'      =>  'required'
		]);

		UsefulLink::create([
			'title'         =>  $request->title,
			'link'          =>  $request->link,
		]);

		session()->flash('success', 'UsefulLink added successfully!');
		return redirect()->route('link.index');
	}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usefulLink = UsefulLink::findOrFail($id);
        return view('backend.usefulLinks.edit', compact('usefulLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
	{
		$request->validate([
			'title'      =>  'required',
			'link'      =>  'required'
		]);

        $usefulLink = UsefulLink::findOrFail($id);

		$usefulLink->update([
			'title'         =>  $request->title,
			'link'          =>  $request->link,
		]);

		session()->flash('success', 'UsefulLink updated successfully!');
		return redirect()->route('link.index');
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
	{
        $usefulLink = UsefulLink::findOrFail($id);
		$usefulLink->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'UsefulLink deleted successfully!'
        ]);
	}
}
