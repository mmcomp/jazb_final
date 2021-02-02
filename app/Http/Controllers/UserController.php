<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Group;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request){
        Auth::logout();
        if($request->method()=='GET'){
            return view('layouts.login');
        }

        if($request->input('email')==null || $request->input('password')==null){
            return view('layouts.login', [
                "error" => new Exception(config('Constants.errors.inavlid_input'))
            ]);
        }

        try{
            if(Auth::attempt(["email"=>$request->input('email'), "password"=>$request->input('password')])){
                return redirect('/');
            }else{
                return view('layouts.login', [
                    "error" => new Exception(config('Constants.errors.inavlid_email_password'))
                ]);
            }
        }catch(Exception $error){
            return view('layouts.login', [
                "error" => $error
            ]);
        }
    }

    public function index(Request $request){
        $users = User::where('is_deleted', false)->with('group')->get();
        $name = null;
        if($request->getMethod() == 'POST'){
            if($request->input('name')!=null){
                $name = trim($request->input('name'));
                $users = User::where(DB::raw('CONCAT(first_Name, " ", last_Name)'),'like','%'.$name.'%')->get();
            }
        }
        if($request->getMethod() == 'GET'){
            return view('users.index',[
                'users' => $users,
                'route' => 'user_alls',
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }else{
            $req =  $request->all();
            if(!isset($req['start'])){
                $req['start'] = 0;
                $req['length'] = 10;
                $req['draw'] = 1;
            }
            $data = [];
            foreach($users as $index => $item){

                $data[] = [
                    $index+1,
                    $item->id,
                    $item->email,
                    $item->first_name,
                    $item->last_name,
                    ($item->group)?$item->group->name:'-',
                    '<a class="btn btn-primary" href="'.route('user_all_edit',$item->id).'"> ویرایش</a>
                     <a class="btn btn-danger" onclick="destroy(event)" href="'.route('user_all_delete',$item->id).'">حذف</a>'
                ];
            }


            $outdata = [];
            for($i = $req['start'];$i<min($req['length']+$req['start'], count($data));$i++){
                $outdata[] = $data[$i];
            }

            $result = [
                "draw" => $req['draw'],
                "data" => $outdata,
                "recordsTotal" => count($users),
                "recordsFiltered" => count($users)
            ];

            return $result;
        }

    }

    public function create(Request $request)
    {
        $groups = Group::all();
        $user = new User;
        if($request->getMethod()=='GET'){
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user
            ]);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->groups_id = (int)$request->input('groups_id');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->gender = $request->input('gender');
        $user->national_code = $request->input('national_code');
        $user->education = $request->input('education');
        $user->major = $request->input('major');
        $user->home_phone = $request->input('home_phone');
        $user->mobile = $request->input('mobile');
        $user->work_mobile = $request->input('work_mobile');
        $user->home_address = $request->input('home_address');
        $user->work_address = $request->input('work_address');
        $user->max_student = (int)$request->input('max_student');
        if($request->file('image_path')){
            $filename = now()->timestamp . '.' . $request->file('image_path')->extension();
            $user->image_path = $request->file('image_path')->storeAs('supporters', $filename, 'public_uploads');
        }

        $find = User::where('email', $request->input('email'))->first();
        if($find){
            $request->session()->flash("msg_error", "نام کاربری قبلا استفاده شده است!");
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }


        if($request->input('password')!=$request->input('repassword')){
            $request->session()->flash("msg_error", "رمز عبور و تکرار آن باید یکی باشند");
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }

        $user->save();

        $request->session()->flash("msg_success", "کاربر با موفقیت افزوده شد.");
        return redirect()->route('user_alls');
    }

    public function edit(Request $request, $id)
    {
        $groups = Group::all();
        $user = User::where('id', $id)->where('is_deleted', false)->first();
        if($user==null){
            $request->session()->flash("msg_error", "کاربر مورد نظر پیدا نشد.");
            return redirect()->route('user_alls');
        }
        if($request->getMethod()=='GET'){
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user
            ]);
        }
        $old_email = $user->email;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->groups_id = (int)$request->input('groups_id');
        $user->email = $request->input('email');
        if($request->input('password')!=null && $request->input('password')!='')
            $user->password = Hash::make($request->input('password'));

        $user->gender = $request->input('gender');
        $user->national_code = $request->input('national_code');
        $user->education = $request->input('education');
        $user->major = $request->input('major');
        $user->home_phone = $request->input('home_phone');
        $user->mobile = $request->input('mobile');
        $user->work_mobile = $request->input('work_mobile');
        $user->home_address = $request->input('home_address');
        $user->work_address = $request->input('work_address');
        $user->max_student = (int)$request->input('max_student');
        if($request->file('image_path')){
            $filename = now()->timestamp . '.' . $request->file('image_path')->extension();
            $user->image_path = $request->file('image_path')->storeAs('supporters', $filename, 'public_uploads');
        }

        $find = User::where('email', $request->input('email'))->first();
        if($find && $old_email!=$request->input('email')){
            $request->session()->flash("msg_error", "نام کاربری قبلا استفاده شده است!");
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }


        if($request->input('password')!=null && $request->input('password')!='' && $request->input('password')!=$request->input('repassword')){
            $request->session()->flash("msg_error", "رمز عبور و تکرار آن باید یکی باشند");
            return view('users.create', [
                "groups"=>$groups,
                "user"=>$user,
                'msg_success' => request()->session()->get('msg_success'),
                'msg_error' => request()->session()->get('msg_error')
            ]);
        }

        $user->save();

        $request->session()->flash("msg_success", "کاربر با موفقیت بروز شد.");
        return redirect()->route('user_alls');
    }

    public function delete(Request $request, $id)
    {
        $groups = Group::all();
        $user = User::where('id', $id)->where('is_deleted', false)->first();
        if($user==null){
            $request->session()->flash("msg_error", "کاربر مورد نظر پیدا نشد.");
            return redirect()->route('user_alls');
        }
        $user->is_deleted = true;
        $user->save();

        $request->session()->flash("msg_success", "کاربر با موفقیت حذف شد.");
        return redirect()->route('user_alls');
    }
}
