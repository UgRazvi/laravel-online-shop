<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // $admin = Auth::guard('admin')->user();
        // echo '<h1> Hey - ' . $admin->name . ' <br> Welcome To Dashboard   <a href="' . route('admin.logout') . '">LogOut</a> </h1>';
    
        return view("admin.dashboard");
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->user();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
