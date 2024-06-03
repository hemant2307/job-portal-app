<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\jobType;
use App\Models\job;
use App\Models\saved_job;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class accountController extends Controller
{
   public function registration(){
    return view('front.account.registration');
   }


   public function registerProcess(Request $request){
      $validator = Validator::make($request->all(),[
         'name'=>'required',
         'email'=>'required|unique:users',
         'password'=>'required|min:6|same:confirm_password',
         'confirm_password'=>'required',
      ]);

      if($validator->passes()){
                  $user = new User();   
                  $user->name = $request->name;
                  $user->email = $request->email;
                  $user->password = Hash::make($request->password);
                  $user->save();
                  session()->flash('success','you have successfully registered');
                  // return redirect()->route('account.login');

         return response()->json([
            'status' => true,
            'errors'=> []
         ]);
      }else{
         return response()->json([
            'status' => false,
            'errors'=> $validator->errors()
         ]);
      }
   }


     public function login(){
      return view('front.account.login');
     }

     public function authentication(Request $request){

      $validator = validator::make($request->all(),[
         'email'=> 'required',
         'password'=> 'required'
      ]);

      if($validator->passes()){
         if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            return redirect()->route('account.profile');

         }else{
            return redirect()->route('account.login')->with('error','either email/password is incorrect');
         }
      }else{
         return redirect()->route('account.login')->with('error','please enter email and password');
      }
   }


     public function profile(){
      $id = Auth::user()->id;
      $user = User::where('id',$id)->first();
      return view('front.account.profile',['user' => $user]);
     }


     public function updateProfile(Request $request){
      $id = Auth::user()->id;

      $validator = Validator::make($request->all(),[
         'name' => 'required|min:5',
         'email'=> 'required|email|unique:users,email,'.$id.',id',
         'designation'=> 'required',
         'mobile'=> 'required'
      ]);

      if($validator->passes()){

         $user = User::find($id);
         $user->name=$request->name;
         $user->email= $request->email;
         $user->designation= $request->designation;
         $user->mobile= $request->mobile;
         $user->save();

         return response()->json([
            'status'=>true,
            'errors'=> []
         ]);
      }else{
         return response()->json([
            'status'=> false,
            'errors' => $validator->errors()
         ]);
      }
}

public function changeProfilePic(Request $request){
   $id = Auth::user()->id;

   $validator = validator::make($request->all(),[
      'image'=> 'required|image'
   ]);
   if($validator->passes()){
      $image = $request->image;
      $ext = $image->getClientOriginalExtension();
      $imageName = $id.time().'.'.$ext;
      $image->move(public_path('/profile_pic/'),$imageName);
     
      $source_path = public_path('/profile_pic/'.$imageName);
      $manager = new ImageManager(Driver::class);
      $image = $manager->read($source_path);

      // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
      $image->cover(150, 150);
      $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

      // delete old pics from the data

      File::delete(public_path('profile_pic/'.Auth::User()->image));
      File::delete(public_path('profile_pic/thumb'.Auth::User()->image));

      // update the pic in database
      user::where('id',$id)->update([
         'image'=>$imageName
      ]);

      session()->flash( 'success','Image Uploaded Successfully');

      return response()->json([
         'status'=> true,
         'errors'=> []
      ]);
   }else{
      return response()->json([
         'status'=> false,
         'errors'=> $validator->errors()
      ]);
   }

}

     public function logout(){
      Auth::logout();
      return redirect('/home-page');
     }

    public function createJob(){
      $catagories = category::orderBy('category','ASC')->where('status',1)->get();
      $jobTypes = jobType::orderBy('job_type','ASC')->where('status',1)->get();

    return view('front.account.job.create',compact('catagories', 'jobTypes'));
}  

public function saveJob(Request $request){
   $rules=[
      'title' => 'required|min:5|max:255',
      'category'=>'required',
      'jobType'=>'required',
      'vacancy'=>'required',
      'location'=>'required',
      'experiance'=>'required',
      'company_name'=> 'required',
   ] ;

   $validator = validator::make($request->all(),$rules);

   if($validator->passes()){

      $job = new Job();
      $job->title = $request->title;
      $job->category_id = $request->category;
      $job->job_type_id= $request->jobType;
      $job->user_id = Auth::user()->id;
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


public function myJob(){

   $jobs = job::where('user_id',Auth::user()->id)->with('jobType')->orderby('created_at','desc')->paginate(10);
   return view('front.account.job.myjob',compact('jobs'));
}

public function editJob(Request $request , $id){

   $catagories = category::orderBy('category','ASC')->where('status',1)->get();
   $jobTypes = jobType::orderBy('job_type','ASC')->where('status',1)->get();

   $jobs = job::where([
      'user_id' => Auth::user()->id ,
      'id'=> $id
   ])->first();
   if($jobs  == null){
      abort(404);
   }
    return view('front.account.job.editjob', compact('catagories','jobTypes','jobs'));
}

public function updateJob(Request $request , $id){
   $rules=[
      'title' => 'required|min:5|max:255',
      'category'=>'required',
      'jobType'=>'required',
      'vacancy'=>'required',
      'location'=>'required',
      'experiance'=>'required',
      'company_name'=> 'required',
   ] ;

   $validator = validator::make($request->all(),$rules);

   if($validator->passes()){

      $job = job::find($id);
      $job->title = $request->title;
      $job->category_id = $request->category;
      $job->job_type_id= $request->jobType;
      $job->user_id = Auth::user()->id;
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

  public function deleteJob(Request $request){
   $job = job::where([
      'user_id'=> Auth::user()->id,
      'id'=> $request->jobId
   ])->first();

   if($job) { 
      job::where('id',$request->jobId)->delete();
      session()->flash('success','job has deleted successfully');
      return response()->json([
         'status'=>true
      ]);
   }else{
      session()->flash('error','either job has deleted or not found');
      return response()->json([
         'status'=>true
      ]);
   }
}


public function savedJob(){
  $savedJobs =  saved_job::where(['user_id'=>Auth::user()->id])
                                ->with(['job','job.jobType','job.Application'])
                                 ->orderBy('created_at' , 'DESC')->paginate(8);

  return view('front.account.job.saved_jobs', compact('savedJobs'));

}


public function removeSavedJob(Request $request){
   $id = $request->id;
      $removeSavedJob = saved_job::where(['id'=> $id , 'user_id'=>Auth::user()->id])->first();

      if($removeSavedJob == null){
         session()->flash('error','No save Job Application Found!');
         return response()->json([
            'status'=> false,
            'message'=> 'No save Job Application Found!'
         ]);
      }

      saved_job::find($id)->delete();
      session()->flash('error','Job save Application removed!');
      return response()->json([
         'status'=> true,
         'message'=> ' Job save Application removed!'
      ]);
}


public function changepassword(Request $request ){
   $validator = Validator::make($request->all(),[
      'old_password'=> 'required',
      'new_password'=> 'required|min:6',
      'confirm_password'=> 'required|same:new_password'
   ]);

   if($validator->fails()){
      return response()->json([
         'status'=>false,
         'errors'=>$validator->errors()
      ]);
   }
    
      // old password check
      if(Hash::check($request->old_password , Auth::user()->password)  == false){
         session()->flash('error','your old password is incorrect');
         return response()->json([
            'status'=>true
         ]);
      }
  
      $user = User::find(Auth::user()->id);
      $user->password = Hash::make($request->new_password);
      $user->save();

      session()->flash('success','your password has changed successfully');
      return response()->json([
         'status'=>true
      ]);
}

}
