<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function electionManage() {

        $data = DB::table('election_management')->first();
        return view('backend.election.view' , compact('data'));

    }


    public function electionUpdate(Request $req) {

        $req->validate([
            'election_date' => 'required|date',
            'voat_start_time' => 'required',
            'voat_end_time' => 'required',
        ]);

        // Update or Insert single row (id = 1)
        DB::table('election_management')->updateOrInsert(
            ['id' => 1],
            [
                'election_date' => $req->election_date,
                'election_start_time' => $req->voat_start_time,
                'election_end_time' => $req->voat_end_time,
                'updated_at' => now(),
                'created_at' => now(), // just in case insert হয়
            ]
        );

        return back()->with('success', 'ডাটা সফল ভাবে আপডেট হয়েছে');


    }


}
