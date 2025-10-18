<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class HomeController extends Controller
{
   public function home()
    {
        $categories = DB::table("categories")
            ->where('categories.status', 1)
            ->leftJoin("candidates", "categories.id", "=", "candidates.category_id")
            ->leftJoin("employees", "candidates.employee_id", "=", "employees.id")
            ->leftJoin("designations", "employees.designation", "=", "designations.id")
            ->leftJoin("offices", "employees.working_place", "=", "offices.id")
            ->select(
                "categories.id as category_id",
                "categories.name as category_name",
                "employees.id as emp_id",
                "employees.name as emp_name",
                "employees.photo as emp_photo",
                "designations.name as designation_name",
                "offices.office_name as office_name"
            )
            ->whereNotNull("employees.id")
            ->orderBy("categories.id")
            ->get()
            ->groupBy("category_id");

        return view('frontend.index', compact('categories'));
    }


   public function results(){
    
        $categories = DB::table('categories')
        ->join('candidates', 'categories.id', '=', 'candidates.category_id') // inner join ensures only categories with candidates
        ->select(
            'categories.id as category_id',
            'categories.name as category_name',
            'candidates.id as candidate_id',
            'candidates.employee_id'
        )
        ->orderBy('categories.id')
        ->get()
        ->groupBy('category_id'); // grouped by category


    return view('frontend.result', compact('categories'));
}




}
