<?php

namespace App\Http\Controllers\Userend;

use App\Models\Vote;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class VotingController extends Controller
{
    public function candidateList()
    {
        $employeeId = session('employee_id'); 

        // Fetch categories with candidates, employees, designations, offices and votes
        $categories = DB::table("categories")
            ->where('categories.status', 1)
            ->leftJoin("candidates", "categories.id", "=", "candidates.category_id")
            ->leftJoin("employees", "candidates.employee_id", "=", "employees.id")
            ->leftJoin("designations", "employees.designation", "=", "designations.id")
            ->leftJoin("offices", "employees.working_place", "=", "offices.id")
            ->leftJoin("votes", function ($join) use ($employeeId) {
                $join->on("votes.candidate_id", "=", "employees.id")
                     ->where("votes.employee_id", "=", $employeeId);
            })
            ->select(
                "categories.id as category_id",
                "categories.name as category_name",
                "categories.max_votes",
                "employees.id as emp_id",
                "employees.name as emp_name",
                "employees.photo as emp_photo",
                "designations.name as designation_name",
                "offices.office_name as office_name",
                "votes.id as voted_id"
            )
            ->whereNotNull("employees.id")
            ->orderBy("categories.id")
            ->get()
            ->groupBy("category_id");

        // Attach voted count per category
        foreach ($categories as $categoryId => $items) {
            $votedCount = $items->where('voted_id', '!=', null)->count();
            $first = $items->first();
            if($first) {
                $first->voted_count = $votedCount;
            }
        }

        // Fetch election start/end time
        $election = DB::table('election_management')->first();

        if($election) {
            $vote_start = Carbon::parse($election->election_date . ' ' . $election->election_start_time, 'Asia/Dhaka');
            $vote_end   = Carbon::parse($election->election_date . ' ' . $election->election_end_time, 'Asia/Dhaka');
        } else {
            // fallback in case election not set
            $vote_start = Carbon::now('Asia/Dhaka');
            $vote_end   = Carbon::now('Asia/Dhaka');
        }

        $time_now = Carbon::now('Asia/Dhaka');

        return view('userend.candidate.view', compact('categories', 'vote_start', 'vote_end', 'time_now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'candidate_id' => 'required|exists:employees,id',
        ]);

        $employeeId = session('employee_id');

        if (!$employeeId) {
            return response()->json([
                'status' => false,
                'message' => 'আপনি লগইন করেননি!'
            ]);
        }

        $category = Category::findOrFail($request->category_id);

        $voteCount = Vote::where('employee_id', $employeeId)
                        ->where('category_id', $request->category_id)
                        ->count();

        if ($voteCount >= $category->max_votes) {
            return response()->json([
                'status' => false,
                'message' => 'আপনি এই পদের জন্য সর্বোচ্চ ভোট দিয়েছেন।',
                'voted_count' => $voteCount
            ]);
        }

        $alreadyVoted = Vote::where('employee_id', $employeeId)
                            ->where('candidate_id', $request->candidate_id)
                            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'status' => false,
                'message' => 'আপনি ইতিমধ্যে এই প্রার্থীকে ভোট দিয়েছেন।',
                'voted_count' => $voteCount
            ]);
        }

        Vote::create([
            'employee_id' => $employeeId,
            'category_id' => $request->category_id,
            'candidate_id' => $request->candidate_id,
        ]);

        $newVoteCount = $voteCount + 1;

        return response()->json([
            'status' => true,
            'message' => 'ভোট সফলভাবে প্রদান হয়েছে।',
            'voted_count' => $newVoteCount,
            'max_votes' => $category->max_votes
        ]);
    }

    public function results($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $winners = Vote::select('candidate_id', DB::raw('COUNT(*) as total_votes'))
            ->where('category_id', $categoryId)
            ->groupBy('candidate_id')
            ->orderByDesc('total_votes')
            ->take($category->max_pass)
            ->with('candidate:id,name,photo')
            ->get();

        return view('results', compact('category', 'winners'));
    }
}
