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

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function profile()
    {
        echo 'Profile page';
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
