<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\WorkCategory;
use App\Models\Recruiter;
use App\Models\Job;
use App\Models\JobTask;
use App\Models\JobStatus;
use Session;

class HomeController extends Controller
{
    public function index($user_id)
    {
        $user=Recruiter::where('id',$user_id)->first();
        if(!isset($user))
        {
            abort(404);
        }
        $closed_id=closedStatus();
        $job_list=Job::where("job_status_id","!=",$closed_id)->get();
        $work_list=WorkCategory::all();
        return view("front.home",get_defined_vars());
    }

    public function redirect()
    {
        return redirect()->route('login');
    }

    public function save_job(Request $req)
    {
        for($i=0;$i<$req->item_count;$i++)
        {
            $job=Job::find($req->job_id[$i]);
            $job->name=$req->name;
            $job->save();
            JobTask::create(['job_id'=>$job->id,
                'work_category_id'=>$req->work_category_id[$i],
                'work_comment'=>$req->work_comment[$i],
                'hours_spent'=>$req->job_hours[$i],
                'recruiter_id'=>$req->recruiter_id,
                'booked_date'=>$req->booked_date]);
        }
        Session::flash("success","Thanks For Submission");
        return response()->json();
    }
}
