<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }
}
