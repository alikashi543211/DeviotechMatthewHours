<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function job_source()
    {
        return $this->belongsTo('App\Models\JobSource');
    }

    public function job_status()
    {
        return $this->belongsTo('App\Models\JobStatus');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    

    public function tasks()
    {
        return $this->hasMany('App\Models\JobTask');
    }
}
