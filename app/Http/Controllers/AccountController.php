<?php

namespace App\Http\Controllers;

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
