<?php

namespace App\Http\Controllers\User;

use Auth;
use Hash;
use File;
use App\Models\{User};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRegistrationUpdateRequest;
use DB;

class ProfileController extends Controller{

    public function profile(){
        $user = User::find(auth()->id());
        return view('dashboard.user.owner-profile.index', compact('user'));
    }
   
    public function profileUpdate(UserRegistrationUpdateRequest $request, $id){
        /* ========== User Table ========== */
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $user->email;
        $user->update();

        if($request->hasFile('image') && $request->file('image')->isValid()){
            // $user->media()->delete();
            DB::table('media')->where('model_id',$id)->delete();
            // $user->clearMediaCollection('image');
            $user->addMediaFromRequest('image')->toMediaCollection('image');
        }

        $request->session()->flash('success', 'Your profile has been updated successfully.');
        return redirect()->back();
    }

    public function changePassword(){
        return view('dashboard.admin.change-password.index');
    }

    public function passwordUpdate(Request $request){
        $this->validate($request, [
            'old_password' => 'required|string|min:8',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $user =  User::find(Auth::id());
        $check = Hash::check($request->old_password, $user->password);

        if($check) {
            $user->password = Hash::make($request->password);
            $user->update();
            $request->session()->flash('success', 'Password Changed Successfully.');
            return redirect()->route('user.changePassword');
        } else {
            $request->session()->flash('danger', 'Old Password does not match.');
            return redirect()->route('user.changePassword'); 
        }
    }
}