<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
   
    public function registerIndex()
    {
        return view('front.account.register');
    }

    public function loginIndex()
    {
        return view('front.account.login');
    }

    public function registeration(Request $request)
    {
       $validatator = Validator::make($request->all(),[
          'name'=>'required',
          'email'=>'required|email|unique:users,email',
          'password'=>'required|min:5',
          'confirm_password'=>'required|same:password'
        ]);

        if($validatator->passes()){
            User::create([
                'name' => $request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)
            ]);

            session()->flash('success','You have registered Successfully');

            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);
               
        }else{
           return response()->json([
               'status'=>false,
                'errors'=>$validatator->errors()
           ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validator->passes()){
           if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                return redirect()->route('account.profile');
           }  else{
              return redirect()->route('account.login')->with('error','Either Email/Password is incorrect');
           }    
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;
        
        $user = User::where('id',$id)->first();

        return view('front.account.profile',compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->passes()){
            
              $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
             $user->save();

            session()->flash('success','Profile Updated Successfully');

            return response()->json([
                'status'=>true,
                 'errors'=>[]
            ]);
        }else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);

        }      

    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $request){

         $id = Auth::user()->id;

         $validator = Validator::make($request->all(),[
             'image'=> 'required|image'
         ]);

         if($validator->passes()){
            $image=$request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.date('Ymd').'-'.$ext;
            $image->move(public_path('/profile-pic'),$imageName);

            User::where('id',$id)->update(['image'=>$imageName]);

            session()->flash('success','Profile Picture Updated Successfully');

            return response()->json([
               'status'=>true,
               'erros'=>[]
            ]);

         }else{
            return response()->json([
                'status'=> false,
                 'errors'=>$validator->errors()
            ]);
         }
    }

    public function createJob()
    {
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.create',compact('categories','jobtypes'));
    }

    
   public function saveJob(Request $request){
    
      $validator = Validator::make($request->all(),[
        'title'=>'required|min:5|max:200',
        'category'=>'required',
        'jobtype'=>'required',
        'vacancy'=>'required|integer',
        'location'=>'required|max:50',
        'description'=>'required',
        'company_name'=>'required|min:3|max:75'

      ]);

      if($validator->passes()){
        Job::create([
           'title'=>$request->title,
           'category_id'=>$request->category,
           'job_types_id'=>$request->jobtype,
          'user_id'=> Auth::user()->id,
           'salary'=>$request->salary,
           'vacancy'=>$request->vacancy,
           'description'=>$request->description,
           'location'=>$request->location,
           'benefits'=>$request->benefits,
           'qualifications'=>$request->qualifications,
           'keywords'=>$request->keywords,
           'responsibility'=>$request->responsibility,
           'experience'=>$request->experience,
           'company_name'=>$request->company_name,
           'company_location'=>$request->company_location,
           'company_website'=>$request->company_website,
        ]);

        session()->flash('success','Job added successfully');

        return response()->json([
           'status'=>true,
           'errors'=>[]
        ]);
         
      }else{
        return response()->json([
           'status'=>false,
           'errors'=>$validator->errors() 
        ]);
      }
   }

    public function updateJob(Request $request, Job $jobId){
    
      $validator = Validator::make($request->all(),[
        'title'=>'required|min:5|max:200',
        'category'=>'required',
        'jobtype'=>'required',
        'vacancy'=>'required|integer',
        'location'=>'required|max:50',
        'description'=>'required',
        'company_name'=>'required|min:3|max:75'

      ]);

      if($validator->passes()){
        $jobId->update([
           'title'=>$request->title,
           'category_id'=>$request->category,
           'job_types_id'=>$request->jobtype,
          'user_id'=> Auth::user()->id,
           'salary'=>$request->salary,
           'vacancy'=>$request->vacancy,
           'description'=>$request->description,
           'location'=>$request->location,
           'benefits'=>$request->benefits,
           'qualifications'=>$request->qualifications,
           'keywords'=>$request->keywords,
           'responsibility'=>$request->responsibility,
           'experience'=>$request->experience,
           'company_name'=>$request->company_name,
           'company_location'=>$request->company_location,
           'company_website'=>$request->company_website,
        ]);

        session()->flash('success','Job Update successfully');

        return response()->json([
           'status'=>true,
           'errors'=>[]
        ]);
         
      }else{
        return response()->json([
           'status'=>false,
           'errors'=>$validator->errors() 
        ]);
      }
   }

   public function myJob(){

      $jobs = Job::where('user_id',Auth::user()->id)
      ->with('jobType')
      ->orderBy('created_at','DESC')
      ->paginate(10);

       return view('front.account.job.my-jobs',compact('jobs'));
   }

    public function editJob(Request $request , $id){

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $job = job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
        ])->first();

        if($job==null){
            abort(404);
        }

        return view('front.account.job.edit',compact('categories','jobtypes','job'));
    }

    public function deleteJob(Job $jobId){

         $jobId->delete();

         session()->flash('success','Job deleted successfully');

         return response()->json([
            'status'=> true,
            'message'=>'Deleted successfully'
         ]);
    }

    public function myJobApplications(){

       $jobs = JobApplication::where('user_id',Auth::user()->id)->get();
        return view('front.account.job.my-job-applications',compact('jobs'));
    }
}
