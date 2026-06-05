<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request){
        $categories = Category::where('status',1)->get();

        $jobtypes = JobType::where('status',1)->get();

        $jobs = Job::where('status',1);
                  
        
        if(!empty($request->keyword)){
            $jobs = $jobs->where(function($query) use ($request){
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }
        if(!empty($request->location)){
            $jobs = $jobs->where('location',$request->location);             
        }
        if(!empty($request->experience)){
            $jobs = $jobs->where('experience',$request->experience);             
        }
        $jobTypeArray=[];
        if(!empty($request->jobtype)){
            $jobTypeArray = explode(',',$request->jobtype);
            $jobs=$jobs->whereIn('job_types_id',$jobTypeArray);             
        }
        
        $jobs = $jobs->with(['jobType','category']);

        if( $request->sort == 0){

             $jobs= $jobs->orderBy('created_at','ASC');
        }else{

            $jobs= $jobs->orderBy('created_at','DESC');

        }
         $jobs=$jobs->paginate(9);

        return view('front.jobs',compact('categories','jobtypes','jobs','jobTypeArray'));
    }
}
