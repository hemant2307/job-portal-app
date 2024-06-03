<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\job;
use App\Models\jobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class jobController extends Controller
{
   public function  index(){

  $jobs = job::orderBy('created_at','asc')->with('User','Application')->paginate(5);
    return view('admin.jobs.list', compact( 'jobs'));

   }

   public function edit($id){

      $jobs = job::find($id);
      $catagories = category::orderBy('category','asc')->get();
      $jobTypes = jobType::orderBy('job_type','asc')->get();

      return view('admin.jobs.edit',compact('jobs','catagories', 'jobTypes' ) );

   }


   public function update(Request $request , $id){
      $rules=[
         'title' => 'required|min:5|max:255',
         'category'=>'required',
         'jobType'=>'required',
         'vacancy'=>'required',
         'location'=>'required',
         'experiance'=>'required',
         'company_name'=> 'required',
      ] ;
   
      $validator = Validator::make($request->all(),$rules);
   
      if($validator->passes()){
   
         $job = job::find($id);
         $job->title = $request->title;
         $job->category_id = $request->category;
         $job->job_type_id= $request->jobType;
       
         $job->vacancy = $request->vacancy;
         $job->salary = $request->salary;
         $job->location = $request->location;
         $job->description = $request->description;
         $job->benifits = $request->benefits;
         $job->responsability = $request->responsibility;
         $job->qualification = $request->qualifications;
         $job->keyword = $request->keywords; 
         $job->experiance = $request->experiance;
         $job->company_name = $request->company_name;
         $job->company_location = $request->company_location;
         $job->company_website = $request->company_website;
         $job->save();
         
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


   public function destroy(Request $request){

      $id = $request->jobId;

      $job = job::find($id);

      if($job == null){
         session()->flash('error','job not found');
         return response()->json([
            'status' => false,
         ]);
      }

      $job->delete();
      session()->flash('success','job Deleted successfully');
      return response()->json([
         'status' => true,
      ]);

   }




}
