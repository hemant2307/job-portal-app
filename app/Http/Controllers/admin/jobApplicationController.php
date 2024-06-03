<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\jobApplication;
use Illuminate\Http\Request;

class jobApplicationController extends Controller
{
   public function index(){

    $applications = jobApplication::orderBy('applied_date','desc')
                        ->with('user', 'job','employer') 
                        ->paginate(5);
                      
    return view('admin.jobApplication.list',compact('applications'));

   }

   public function destroy(Request $request){

    $id = $request->jobId;

    $application = jobApplication::find($id);
    if($application == null){
        session()->flash( "error", "Job Application not found!");
        return response()->json([
            'status'=> false
        ]);

        $application->delete();
        session()->flash( "success", "Job Application deleted");
        return response()->json([
            'status'=> true
        ]);

    }

   }



}
