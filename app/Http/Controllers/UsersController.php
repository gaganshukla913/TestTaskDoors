<?php

namespace App\Http\Controllers;

use Auth; 
use File;
use Hash;
use Input;
use Cache;
Use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Requests\{UserRegistrationRequest, UserRegistrationUpdateRequest};
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-section');
        $this->middleware('permission:user-index', ['only'=>['index']]);   
        $this->middleware('permission:user-create', ['only'=>['create','store']]);
        $this->middleware('permission:user-edit', ['only'=>['edit', 'update']]);
        $this->middleware('permission:user-destroy', ['only'=>['destroy']]);
        $this->middleware('permission:user-proxy',  ['only'=>['proxyLogin']]);
    }

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
       
            $users = User::where('menuroles', 'user');
            if($request->name && $request->name != null){
                $users = $users->where('name', 'like', '%' . $request->name . '%');
            }

            if($request->email && $request->email != null ){
                $users = $users->where('email', 'like', '%' . $request->email . '%');
            }

            if(auth()->guard('admin')->check()){
                $users = $users->orderBy('id', 'DESC')->paginate(20);
            }else{
                $users = $users->where('id','!=',auth()->guard('web')->id() )->orderBy('id', 'DESC')->paginate(20);
            }
            
            return view('dashboard.admin.usersList', compact('users'));
    }
    
    public function create(){
        $roles = Role::select('name')->where('guard_name','!=','admin')->get();
        return view('dashboard.admin.userAdd',compact('roles'));
    }

    public function store(UserRegistrationRequest $request){
        /* ========== User Table ========== */
        $user = new User;
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->status = 1;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole($request['role']);

        $request->session()->flash('success', 'User has been registered successfully.');
        if(auth()->guard('admin')->check()){
            return redirect()->route('users.index');
        }else{
            return redirect()->to('/users');   
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $user = User::find($id);
        return view('dashboard.admin.userShow', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        if($id > 0){
            $user = User::with('roles')->find($id);
            $roles = Role::select('name')->where('guard_name','!=','admin')->get();
            return view('dashboard.admin.userEditForm', compact('user','roles'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRegistrationUpdateRequest $request, $id){
        /* ========== User Table ========== */
        $user = User::with('roles')->find($id);
        $user->name = $request->name;
        // $user->email = $request->email;
        $user->update();
        if ( $user['roles'] != '' && $user['roles'][0]['name'] != $request->role ) {
            $user->removeRole($user['roles'][0]['name']);
            $user->assignRole($request->role);
        }
        $request->session()->flash('success', 'User profile updated successfully.');
        if(auth()->guard('admin')->check()){
            return redirect()->route('users.index');
        }else{
            return redirect()->to('/users');   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if($id > 0){
            $user = User::find($id);
            if($user){
                $user->delete();
            }
            session()->flash('danger', 'User profile has been deleted successfully.');

         if(auth()->guard('admin')->check()){
                return redirect()->route('users.index');
            }else{
                return redirect()->to('/users');   
            }
        }
    }

    public function status($id) {
        $user = User::find($id);
        if($user->status == '1') {
            $user->status = '0';
            $user->update();
            return response()->json(['status'=>'success','message'=>'User Rejected','type'=>'deactivate']);
        } else {
            $user->status = '1';
            $user->update();
            return response()->json(['status'=>'success','message'=>'User Approved','type'=>'activate']);
        }
    }
}
