<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\JobSource;
use App\Models\JobStatus;
use App\Models\Client;
use App\Models\JobTask;
use Session;

class JobTaskController extends Controller
{
    public function index(Request $req)
    {
        $status_list=JobStatus::all();
        if(isset($req->filter))
        {
            $list=Job::where('id','!=',0);
            if(isset($req->job_status_id))
            {
                $list->where("job_status_id",$req->job_status_id);
            }
            if(isset($req->category_id))
            {
                $list->where("category_id",$req->category_id);
            }
            if(isset($req->client_id))
            {
                $list->where("client_id",$req->client_id);
            }if(isset($req->from_date) && isset($req->to_date))
            {
                $to_date = date("Y-m-d", strtotime($req->to_date));
                $from_date = date("Y-m-d", strtotime($req->from_date));
                $list->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }if(isset($req->from_date))
            {
                $to_date = date("Y-m-d");
                $from_date = date("Y-m-d", strtotime($req->from_date));
                $list->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }if(isset($req->to_date))
            {
                $first_job=Job::first();
                $to_date = date("Y-m-d", strtotime($req->to_date));
                $from_date=explode(" ",$first_job->created_at)[0];
                $list->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }
            $job_ids=$list->pluck('id')->toArray();
        }else
        {
            $job_ids=Job::pluck('id')->toArray();

        }
        $list=JobTask::whereIn('job_id',$job_ids)->get();
        $client_list=Client::all();
        $category_list=Category::all();
    	return view("admin.job_task.index",get_defined_vars());
    }
}
