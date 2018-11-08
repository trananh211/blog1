<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User; // this to add User Model
use Illuminate\Support\Facades\Hash; // this to check Hash Password
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
    	if ($request->isMethod('post'))
    	{
    		$data = $request->input();
    		if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'admin'=>'1']))
    		{
    			// Session::put('adminSession',$data['email']);
    			return redirect('admin/dashboard');
    		}
    		else
    		{
    			return redirect('admin')->with('error','Sai địa chỉ Email hoặc Mật khẩu');
    		}
    	}
    	return view('admin.admin_login');
    }

    public function dashboard()
    {
    	// if (Session::has('adminSession'))
    	// {
    	// 	return view('admin/dashboard');
    	// } 
    	// else 
    	// {
    	// 	return redirect('admin')->with('error','Bạn cần đăng nhập trước khi truy cập');
    	// }
    	return view('/admin/dashboard');
    }

    public function setting()
    {
    	return view('/admin/setting');
    }

    public function register()
    {
        return view('/auth/register');
    }

    public function checkPass(Request $request) 
    {
        if (Auth::check())
        {
            $id = Auth::id();
            $data = $request->all();
            $current_pwd = $data['current_pwd'];
            $check_pwd = User::where(['id'=>$id])->first();
            if(Hash::check($current_pwd, $check_pwd->password)) {
                echo 'true';die();
            } else {
                echo 'false';die();
            }
        }
    }

    public function updatePass(Request $request)
    {
        if (Auth::check())
        {
            $id = Auth::id();
            $data = $request->all();
            $current_pwd = $data['current_pwd'];
            $check_pwd = User::where(['id'=>$id])->first()->password;
            if(Hash::check($current_pwd, $check_pwd)) {
                $password = bcrypt($data['pwd']);
                DB::table('users')->where('id',$id)->update(['password' => $password]);
                return redirect('/admin/setting')->with('success','Update password successfully!');
            } else {
                return redirect('/admin/setting')->with('error','Current password incorrect.');
            }
        }
        
    }

    public function logout()
    {
    	Session::flush();
    	return redirect('/admin')->with('success','Đăng xuất thành công');
    }
}
