<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\job;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index(){
        $categories = category::where('status',1)->orderby('category','asc')->take(5)->get();

        $newcategories = category::where('status',1)->orderby('category','asc')->get();
        
        $isfeatureds = job::where('status',1)->where('isFeatured',1)
                       ->orderby('created_at','desc')
                       ->with('jobType')
                       ->take(6)->get();
        $latestjobs = job::where( 'status',1)->orderBy('created_at','desc')
                      ->with('jobType')
                      ->take(6)->get();

        return view("front.home", compact('categories','isfeatureds', 'latestjobs','newcategories'));
    }

    // public function index(){
    //     return view("front.home");
    // }


}
