<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

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
        ->whereNotNull("employees.id") // শুধু যাদের নিচে প্রার্থী আছে
        ->orderBy("categories.id")
        ->get()
        ->groupBy("category_id");

    return view('frontend.index', compact('categories'));
}


}
