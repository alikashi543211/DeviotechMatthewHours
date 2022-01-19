<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruiter;
use App\Models\Job;
use App\Models\WorkCategory;
use App\Models\Client;

class DashboardController extends Controller
{
    public function dashboard()
    {
    	$user_count=Recruiter::count();
    	$job_count=Job::count();
    	$work_category_count=WorkCategory::count();
    	$client_count=Client::count();
        return view('admin.dashboard.dashboard', get_defined_vars());
    }

    public function delete(Request $req)
    {
        User::find($req->id)->delete();
        return redirect()->back()->with('success', 'User Deleted Successfully!');
    }
}
