<?php

use File as File;
use Carbon\Carbon;
use App\Models\User;
use App\Models\JobStatus;
use App\Models\JobTask;


function uploadFile($file, $path){
    $name = time().'-'.str_replace(' ', '-', $file->getClientOriginalName());
    $file->move($path,$name);
    return $path.'/'.$name;
}

function revenue_per_hour($job){
    $total_hours=$job->tasks->sum('hours_spent');
    if($total_hours!=0)
    {
        $per_hour=twoDecimal($job->job_value/$total_hours);
        return $per_hour;
    }
    return 0;
}

function hours_spent($job)
{
    return $job->tasks->sum('hours_spent');
}

function ClosedStatus(){
	$closed_id=JobStatus::select('id')->where('name','closed')->pluck('id')->first();
     return $closed_id;
}

function twoDecimal($value){
	$foo = $value;
	return  number_format((float)$foo, 2, '.', '');  // Outputs -> 105.00
}

?>
