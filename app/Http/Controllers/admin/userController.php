<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
   public function index(){
    $users = User::orderBy('created_at' , 'ASC')->paginate(10);
    return view( 'admin.users.list', compact('users'));
   }

   public function edit($id){
      $user = User::find($id);
      return view('admin.users.edit',compact( 'user') );

   }

   public function update($id, Request $request) {
     $Validator =  Validator::make($request->all(),[
         'name' => 'required|min:5',
         'email'=> 'required|email|unique:users,email,'.$id.',id',
         'designation'=> 'required',
         'mobile'=> 'required'
      ]);

      if($Validator->passes()){

         $user = User::find($id);
         $user->name=$request->name;
         $user->email= $request->email;
         $user->designation= $request->designation;
         $user->mobile= $request->mobile;
         $user->save();

         session()->flash( 'success','Data Updated Successfully');

         return response()->json([
            'status'=>true,
            'errors'=> []
         ]);
      }else{
         return response()->json([
            'status'=> false,
            'errors' => $Validator->errors()
         ]);
      }
   }


   public function destroy(Request $request){

      $id = $request->userId;

      $user = user::find($id);

      if($user == null){
         session()->flash('error','use not found');
         return response()->json([
            'status'=>false,
         ]);
     }

     $user->delete();
     session()->flash('success','use deleted successfully');
     return response()->json([
        'status'=>true,
     ]);

   }

}
