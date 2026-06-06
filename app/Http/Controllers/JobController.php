<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function detail($id){

    $jobs = Job::where([
          'id'=>$id,
          'status'=>1
    ])->with(['jobType','category'])->first();

    if($jobs==null){
        abort(404);
    }

    return view('front.jobdetails',compact('jobs'));
    }

    public function applyJob(Request $request){
        
        $id = $request->id;

        $job = Job::where('id',$id)->first();

        if($job == null){

            session()->flash('error','Job doesnot exist');

            return response()->json([
                'status'=>false,
                'message'=>'job doesnot exist'
            ]);
        }

        $employer_id = $job->user_id;

        if($employer_id == Auth::user()->id){
            session()->flash('error','You cannot Apply on your Own Job');

            return response()->json([
               'status'=>false,
               'message'=>'You cannot Apply on your Own Job' 
            ]);
        }

        JobApplication::create([
          'job_id'=>$id,
          'user_id'=>Auth::user()->id,
          'employeer_id'=>$employer_id,
          'applied_dates'=>now(),
        ]);

        session()->flash('success','You have successfully applied');

        return response()->json([
          'status'=>true,
          'message'=>'You can not applying on your Job'
        ]);
    }
}
