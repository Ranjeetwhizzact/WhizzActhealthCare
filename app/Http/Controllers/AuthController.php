<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); 
            if ($user->role === 'superadmin') {
                return redirect()->route('dashboard'); // Show index if superadmin
            } else {
                return redirect()->route('assignpatients'); // Redirect if not superadmin
            }
            
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegister() {
        return view('auth.register');
    }
    public function user() {
        $user = User::orderBy('id', 'desc')->paginate(10);
        return view('users',['user'=>$user]);
    }
    public function edituser($id) {
        $user = User::find($id);
        return view('auth.register',['user'=>$user]);
    }

    public function register(Request $request ,$id = null) {
        if(!empty($request->id)){
            $user = User::find($request->id);
        }else{
            $user = new User;
        }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->password = Hash::make($request->password);
            $user->save();

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' =>  'required|email',
        //     'password' =>  'nullable|min:6|confirmed' ,
        //     'role' => 'required|in:superadmin,admin,user'
        // ]);
    
        // $userData = [
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'role' => $request->role,
        //     'password' => $request->password
        // ];
    
        // Only hash password if provided
        // if ($request->filled('password')) {
        //     $userData['password'] = Hash::make($request->password);
        // }
    
        // User::updateOrCreate(['id' => $id], $userData);
        return redirect()->back()->with('success', $id ? 'User updated successfully!' : 'User created successfully!');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

}
