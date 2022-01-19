<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTask extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    public function work_category()
    {
        return $this->belongsTo('App\Models\WorkCategory');
    }

    public function recruiter()
    {
        return $this->belongsTo('App\Models\Recruiter');
    }
}
