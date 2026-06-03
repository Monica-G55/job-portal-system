<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(){
        $categories = Category::where('status',1)->get();

        $jobtypes = JobType::where('status',1)->get();

        $jobs = Job::where('status',1)
                    ->with('jobType')
                    ->orderBy('created_at','DESC')
                    ->paginate(6);

        return view('front.jobs',compact('categories','jobtypes','jobs'));
    }
}
