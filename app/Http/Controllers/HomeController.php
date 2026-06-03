<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories= Category::where('status',1)
                    ->orderBy('name','ASC')
                    ->take(8)
                    ->get();

        $featuredjobs=Job::where('status',1)
                     ->where('isfeatured',1)
                     ->with('jobType')
                     ->orderBy('created_at','DESC')
                     ->take(6)
                     ->get();
        
        $latestjobs=Job::where('status',1)
                     ->orderBy('created_at','DESC')
                    ->with('jobType')
                     ->take(6)
                     ->get();

        return view('front.home',compact('categories','featuredjobs','latestjobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
