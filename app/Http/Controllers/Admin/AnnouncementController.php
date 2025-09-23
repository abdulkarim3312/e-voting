<?php

namespace App\Http\Controllers\Admin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\GlobalSetting;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
	public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-announcement')->only('index');
        $this->middleware('permission:create-announcement')->only(['create', 'store']);
        $this->middleware('permission:edit-announcement')->only(['edit', 'update']);
        $this->middleware('permission:delete-announcement')->only('destroy');
    }
	/**
     * Display a listing of the resource.
     */

	public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Announcement::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function(Announcement $announcement) {
                    $checked = $announcement->status == 1 ? 'checked' : '';
                    return '<input type="checkbox" class="status-toggle big-checkbox" data-id="' . $announcement->id . '" ' . $checked . '>';
                })
                ->addColumn('action', function (Announcement $announcement) {
					$editUrl = route('announcements.edit', $announcement->id);
					$deleteUrl = route('announcements.destroy', $announcement->id);

					$buttons = '';

					if (auth()->user()->can('edit-announcement')) {
						$buttons .= '
							<a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
								<i class="fa fa-edit"></i>
							</a>
						';
					}

					if (auth()->user()->can('delete-announcement')) {
						$buttons .= '
							<form action="' . $deleteUrl . '" method="POST" 
								class="delete-form d-inline" 
								data-id="' . $announcement->id . '" 
								data-name="' . e($announcement->title) . '">
								' . csrf_field() . method_field('DELETE') . '
								<button type="submit" class="btn btn-danger btn-sm deleteItem">
									<i class="fa fa-trash"></i>
								</button>
							</form>
						';
					}

					return $buttons;
				})
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.announcement.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')->get();
        return view('backend.announcement.create', compact('roles'));
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
        $data = $request->validate([
			'title' => 'required',
			'link'  => 'required',
		]);

		Announcement::create($data);

		session()->flash('success', 'Announcement created successfully!');
		return redirect()->route('announcements.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param  \App\Announcement  $announcement
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Announcement $announcement)
	{
		$$announcement = $announcement->find($announcement->id);
		return view('backend.announcement.edit', compact('announcement'));
	}

	/**
	 * Update the specified resource in storage.
	 * @param $id
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Announcement  $announcement
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$data = $request->validate([
			'title' => 'required',
			'link'  => 'required',
		]);

		$announcement = Announcement::findOrFail($id);
		$announcement->update($data);

		session()->flash('success', 'Announcement updated successfully!');
		return redirect()->route('announcements.index');
	}

	/**
	 * Remove the specified resource from storage.
	 * @param $id
	 * @return \Illuminate\Http\Response
	 * @internal param Announcement $Announcement
	 */
	public function destroy($id)
	{
		$announcement = Announcement::findOrFail($id);
		$announcement->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully!']);
	}

	public function statusUpdate( $id )
	{
		$announcement = Announcement::findOrFail($id);

		$newStatus = $announcement->status == 1 ? 0 : 1;
		$announcement->update(['status' => $newStatus]);

		$message = $newStatus == 1
			? 'Announcement is displaying now!'
			: 'Announcement has been archived successfully!';

		return response()->json([
			'success' => true,
			'message' => $message,
			'status' => $newStatus
		]);
	}

	public function AnnouncementSwitch()
	{
        
		$settings_record = GlobalSetting::find(1);
		$newStatus = $settings_record->announcement_status == 0 ? 1 : 0;
		$settings_record->update(['announcement_status' => $newStatus]);

		$message = $newStatus == 1
			? 'Announcements are turned on!'
			: 'Announcements are turned off!';

		return response()->json([
			'success' => true,
			'message' => $message,
			'announcement_status' => $newStatus,
		]);
	}
}
