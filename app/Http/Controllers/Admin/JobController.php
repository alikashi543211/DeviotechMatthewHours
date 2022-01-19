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
use App\Models\Recruiter;
use Session;

class JobController extends Controller
{
    public function index(Request $req)
    {
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
            $list=$list->get();
        }else
        {
            $list=Job::all();
        }
        $status_list=JobStatus::all();
        $client_list=Client::all();
        $category_list=Category::all();
        return view("admin.job.index",get_defined_vars());
    }

    public function add()
    {
        $client_list=Client::all();
    	$category_list=Category::all();
        $source_list=JobSource::all();
        $status_list=JobStatus::all();
    	return view("admin.job.add",get_defined_vars());
    }

    public function edit(Request $req)
    {
        $job=Job::find($req->id);
        $client_list=Client::all();
        $sub_cat_list=SubCategory::where("category_id",$job->category_id)->get();
    	$category_list=Category::all();
        $source_list=JobSource::all();
        $status_list=JobStatus::all();
        return view("admin.job.edit",get_defined_vars());
    }

    public function save(Request $req)
    {
    	if(isset($req->id))
    	{
			Job::where('id',$req->id)->update(['code'=>$req->code,
                'client_id'=>$req->client_id,
                'description'=>$req->description,
                'contact_person'=>$req->contact_person,
                'category_id'=>$req->category_id,
                'sub_category_id'=>$req->sub_category_id,
                'job_value'=>$req->job_value,
                'job_status_id'=>$req->job_status_id,
                'job_source_id'=>$req->job_source_id,
                'comment'=>$req->comment]);
    	}else
    	{
    		Job::create(['code'=>$req->code,
                'client_id'=>$req->client_id,
                'description'=>$req->description,
                'contact_person'=>$req->contact_person,
                'category_id'=>$req->category_id,
                'sub_category_id'=>$req->sub_category_id,
                'job_value'=>$req->job_value,
                'job_status_id'=>$req->job_status_id,
                'job_source_id'=>$req->job_source_id,
                'comment'=>$req->comment,
                'user_id'=>auth()->user()->id]);
    	}
        Session::flash("success","Saved Successfully");
    	return response()->json("Saved Successfully");
    }

    public function delete(Request $req)
    {
    	Job::find($req->id)->delete();
        JobTask::where('job_id',$req->id)->delete();
    	return back()->with("success","Deleted successfully");
    }

    public function tasks(Request $req)
    {
        
        if(isset($req->filter))
        {
            $list=JobTask::where('job_id',$req->id);
            if(isset($req->recruiter_id))
            {
                $list->where("recruiter_id",$req->recruiter_id);
            }
            if(isset($req->from_date))
            {
                $to_date = date("Y-m-d");
                $from_date = date("Y-m-d", strtotime($req->from_date));
                $list->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }if(isset($req->to_date))
            {
                $first_record=JobTask::where('job_id',$req->id)->first();
                $to_date = date("Y-m-d", strtotime($req->to_date));
                $from_date=explode(" ",$first_record->created_at)[0];
                $list->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }
            $list=$list->get();
        }else
        {
            $list=JobTask::where('job_id',$req->id)->get();
        }
        $status_list=JobStatus::all();
        $recruiter_list=Recruiter::all();
        $category_list=Category::all();
        
        return view("admin.job.job_tasks",get_defined_vars());
    }

    // Ajax Method
    public function SubCategory_list(Request $req)
    {
    	$cat=Category::find($req->id);
    	$sub_cat_list=$cat->sub_categories;
    	return view("ajax.admin.subcategory_list",get_defined_vars());
    }
}
