<?php

namespace App\Http\Controllers\Admin;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\PartyTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PartyController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('web')->user();
            return $next($request);
        });

        $this->middleware('permission:view-party')->only('index');
        $this->middleware('permission:create-party')->only(['create', 'store']);
        $this->middleware('permission:edit-party')->only(['edit', 'update']);
        $this->middleware('permission:delete-party')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Party::with('translations')->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('bn_name', function ($party) {
                    return optional($party->translations->where('locale', 'bn')->first())->party_name;
                })

                ->addColumn('en_name', function ($party) {
                    return optional($party->translations->where('locale', 'en')->first())->party_name;
                })

                ->addColumn('bn_president', function ($party) {
                    return optional($party->translations->where('locale', 'bn')->first())->president;
                })

                ->addColumn('bn_secretary', function ($party) {
                    return optional($party->translations->where('locale', 'bn')->first())->secretary_general;
                })

                ->addColumn('created_at', function ($party) {
                    return $party->created_at->toDayDateTimeString();
                })

                ->addColumn('action', function ($party) {
                    $editUrl = route('party.edit', $party->id);
                    $deleteUrl = route('party.destroy', $party->id);

                    $buttons = '';
                    if (auth()->user()->can('edit-party')) {
                        $buttons .= '
                            <a href="' . $editUrl . '" class="btn btn-sm btn-primary text-white">
                                <i class="fa fa-edit"></i>
                            </a>
                        ';
                    }
                    if (auth()->user()->can('delete-party')) {
                        $buttons .= '
                            <form action="' . $deleteUrl . '" method="POST" class="delete-form" data-id="' . $party->id . '" style="display:inline-block;">
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

        return view('backend.party.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.party.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'party_name'    => 'required',
            'symbol_name'   => 'required',
            'party_symbol'  => 'nullable|mimes:jpeg,jpg,png|max:10240'
        ]);

        $filename = 'none';
        if ($request->hasFile('party_symbol')) {
            $filename = $request->file('party_symbol')->store('party_symbol', 'public');
        }

        $party = Party::create([
            'registration_no'       => $request->registration_no,
            'registration_date'     => $request->registration_date,
            'symbol_name'           => $request->symbol_name,
            'party_symbol'          => $filename,
            'phone'                 => $request->phone,
            'mobile'                => $request->mobile,
            'email'                 => $request->email,
            'website'               => $request->website,
        ]);

        PartyTranslation::create([
            'party_id'          => $party->id,
            'locale'            => 'bn',
            'party_name'        => $request->party_name,
            'president'         => $request->president,
            'secretary_general' => $request->secretary_general,
            'chairperson'       => $request->chairperson,
            'chairman'          => $request->chairman,
            'general_secretary' => $request->general_secretary,
            'aamir'             => $request->aamir,
            'bod'               => $request->bod,
            'address'           => $request->address,
        ]);

        PartyTranslation::create([
            'party_id'          => $party->id,
            'locale'            => 'en',
            'party_name'        => $request->en_party_name,
            'president'         => $request->en_president,
            'secretary_general' => $request->en_secretary_general,
            'chairperson'       => $request->en_chairperson,
            'chairman'          => $request->en_chairman,
            'general_secretary' => $request->en_general_secretary,
            'aamir'             => $request->en_aamir,
            'bod'               => $request->en_bod,
            'address'           => $request->en_address,
        ]);

        session()->flash('success', 'Party added successfully');
        return redirect()->route('party.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $party)
    {
        return view('backend.party.edit', compact('party'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $party)
    {
        $request->validate([
            'party_name'    => 'required',
            'symbol_name'   => 'required',
            'party_symbol'  => 'nullable|mimes:jpeg,jpg,png|max:10240'
        ]);

        $filename = 'none';

        if ($request->hasFile('party_symbol')) {
            if ($party->party_symbol && Storage::disk('public')->exists($party->party_symbol)) {
                Storage::disk('public')->delete($party->party_symbol);
            }

            $filename = $request->file('party_symbol')->store('party_symbol', 'public');
        }

        $party->update([
            'registration_no'       => $request->registration_no,
            'registration_date'     => $request->registration_date,
            'symbol_name'           => $request->symbol_name,
            'party_symbol'          => $filename,
            'phone'                 => $request->phone,
            'mobile'                => $request->mobile,
            'email'                 => $request->email,
            'website'               => $request->website,
        ]);

        $bn = PartyTranslation::where('party_id', $party->id)->where('locale', 'bn')->first();
        $en = PartyTranslation::where('party_id', $party->id)->where('locale', 'en')->first();

        if ($bn) {
            $bn->update([
                'party_name'        => $request->party_name,
                'president'         => $request->president,
                'secretary_general' => $request->secretary_general,
                'chairperson'       => $request->chairperson,
                'chairman'          => $request->chairman,
                'general_secretary' => $request->general_secretary,
                'aamir'             => $request->aamir,
                'bod'               => $request->bod,
                'address'           => $request->address,
            ]);
        }

        if ($en) {
            $en->update([
                'party_name'        => $request->en_party_name,
                'president'         => $request->en_president,
                'secretary_general' => $request->en_secretary_general,
                'chairperson'       => $request->en_chairperson,
                'chairman'          => $request->en_chairman,
                'general_secretary' => $request->en_general_secretary,
                'aamir'             => $request->en_aamir,
                'bod'               => $request->en_bod,
                'address'           => $request->en_address,
            ]);
        }

        session()->flash('success', 'Party updated successfully');
        return redirect()->route('party.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $party)
    {
        if ($party->party_symbol && Storage::disk('public')->exists($party->party_symbol)) {
            Storage::disk('public')->delete($party->party_symbol);
        }

        $party->delete();

        return response()->json([
            'status' => true,
            'message' => 'Party deleted successfully!',
        ]);
    }
}
