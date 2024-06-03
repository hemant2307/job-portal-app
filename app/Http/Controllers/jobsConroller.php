<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\job;
use App\Models\jobApplication;
use App\Models\jobType;
use App\Models\saved_job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class jobsConroller extends Controller
{
   public function index(Request $request){

    $catagories = category::where('status' , 1)->orderby('category','asc')->get();
    $jobTypes = jobType::where('status' , 1)->orderby('job_type','asc')->get();

    $jobs = job::where( 'status',1);

   //  search by keyword  (used in group query to execute multiple query on the  same table)
   if(!empty($request->keyword)){
      $jobs = job::where(function($query) use($request){
         $query->orwhere( 'title' , 'like' , '%'.$request->keyword.'%');
         $query->orWhere('keyword' , 'like' , '%'.$request->keyword.'%');
      });
   }

   // search by location
   if(!empty( $request->location )) {
      $jobs = job::where('location',$request->location);
   }

   // search by category
   if(!empty( $request->category )) {
      $jobs = job::where('category_id',$request->category);
   }
   
   $jobTypeArray = [];
    // search by job type
    if(!empty( $request->job_type )) {
     $jobTypeArray =  explode( ',', $request->job_type );
     $jobs = job::whereIn( 'job_type_id' , $jobTypeArray );
   }

    // search by experiance
    if(!empty( $request->experiance )) {
      $jobs = job::where( 'experiance' , $request->experiance );
    }

    $jobs = $jobs->with(['jobType','category']);

    if($request->sort ==0){
      $jobs = $jobs->orderBy('created_at','ASC');
    }else{
      $jobs = $jobs->orderBy('created_at','DESC');
    }
    
    $jobs = $jobs->paginate(8);

    return view('front.jobs',compact('catagories', 'jobTypes', 'jobs','jobTypeArray'));
   }

   public function jobdetail($id){

      $jobs = job::where(['id'=>$id , 'status'=>1])->with('jobType','category')->first();
      if($jobs == null){
         abort(404);
      }

      $count=0;
      if(Auth::user()){
         $count = saved_job::where(['user_id'=>Auth()->user()->id , 'job_id'=>$id])->count();
      }

      $applicants = jobApplication::where( ['job_id' => $id] )->with('user')->get();
      //   dd($applicants);

      return view('front.job-detail',compact( 'jobs','count','applicants'));

   }


   public function jobapply(Request $request){
      $id = $request->id;
      $jobs = job::where(['id'=>$id ])->first();

      // if job is not availabel
      if($jobs==null){
         session()->flash('error','either job not found or it has been deleted');
         return response()->json([
            'status'=> false,
            'message'=> "Job not found"
         ]);
      }

      // if user apply on own job
      $employer_id = $jobs->user_id;

      if(Auth::user()->id == $employer_id ){
         session()->flash('error','You cannot apply your own job!');
         return response()->json([
            'status' =>false,
            'message'=> 'You cannot apply your own job!'
         ]);
      }

      // if user try to apply twice on a same job

      $jobApplicationCount = jobApplication::where(['job_id'=>$id , 'user_id'=>Auth::user()->id])->count( );

      if( $jobApplicationCount > 0 ) {
         session()->flash('error','you have already applied for this job');
         return response()->json([
            'status' =>false,
            'message'=> 'you have already applied for this job'
         ]);
      }

      // save the job  application in database table
      $jobApplication = new jobApplication();
      $jobApplication->job_id = $id;
      $jobApplication->user_id = Auth::user()->id;
      $jobApplication->employer_id = $employer_id ;
      $jobApplication->applied_date = now( ) ;
      $jobApplication->save() ;

      session()->flash('success','You have applied successfully on this job');
       return response()->json([
         'status' =>true,
         'message'=> 'You have applied successfully on this job'
      ]);
   }

   public function appliedJob(){
      $jobApplications = jobApplication::where('user_id',Auth::user()->id)
                              ->with(['job','job.jobType','job.Application'])
                              ->orderBy('created_at' , 'DESC')->paginate(8); 
      // dd($jobApplications);
      return view('front.account.job.applied-job',compact('jobApplications'));
   }

   public function removeJob(Request $request){
      $id = $request->id;
      $jobApplication = jobApplication::where(['id'=> $id , 'user_id'=>Auth::user()->id])->first();

      if($jobApplication == null){
         session()->flash('error','No Job Application Found!');
         return response()->json([
            'status'=> false,
            'message'=> 'No Job Application Found!'
         ]);
      }

      jobApplication::find($id)->delete();
      session()->flash('error','Job Application removed!');
      return response()->json([
         'status'=> true,
         'message'=> ' Job Application removed!'
      ]);
   }


   public function saveJob(Request $request){

      $id = $request->id;
      $job = job::find($id);

      // if job id not found
      if($job == null){
         session()->flash('error','Job Not Found!');
         return response()->json([
            'status' => false,
            'message' => "Job not found!"
         ]);
      }

      //  check user already applied for this job or not 

      $count = saved_job::where(['user_id'=>Auth()->user()->id , 'job_id'=>$id])->count();
     
      if($count >0){
         session()->flash('error','you have already save this job');
         return response()->json([
            'status' => false,
            'message' => "you have already saven this job"
         ]);
      }


      $saved_job = new  saved_job();
      $saved_job ->user_id= Auth() ->user() ->id ;
      $saved_job ->job_id=$id;
      $saved_job ->save();

      session()->flash('error','you have successfully save this job.!');
         return response()->json([
            'status' => true,
            'message' => "you have successfully save this job.!"
         ]);
   }
   


  








   
}
