<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function create_user(Request $request)
    {
    
        $request->validate([
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
        ]);
        $user =User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' =>bcrypt($request->password),
        ]);
        return response()->json([
            'status' =>'true',
            'message' =>'user created successfully',
            'token' =>$user->createToken("API TOKEN")->plainTextToken],200);
        
    
}
        public function view_user()
        {
            $user = User::all();
            return response()->json($user);
            
        }

        public function login(Request $request)
        {
            $request->validate([
                'email' =>'required|email',
                'password' =>'required|min:8',
            ]);
            if(!Auth::attempt($request->only(['email','password'])))
            {
                return response()->json([
                    'status' =>false,
                    'message' =>'Email and password does not match with our record',
                ],401);
                
            }
            $user =User::where('email',$request->email)->first();
         return response()->json([
            'status'=>true,
            'user' =>$user,
            'message' =>'user logged in successfully',
            'token' => $user->createToken('API token of ' . $user->name, ['*'])->plainTextToken],200);
         }
         public function profile_view()
         {
            $user = Auth::user();
            return response()->json([
                'status' =>true,
                'user' => $user,
            ],200);
            return "no user found";
         }

public function logout()
{
    $user =Auth::user()->id;
    $id = User::find($user);
    $id->tokens()->delete();
    return response()->json([
        'status' =>200,
        'message' =>'user logged out successfuly',
    ]);
}
public function add_task(Request $request)
{
    $request->validate([
        'title' =>'required|string',
        'description' =>'required|string',
        'date' =>'required|date',
    ]);
    $user = $request->user();
    Task::create([
        'user_id' =>$user->id,
        'title' =>$request->title,
        'description' =>$request->description,
        'date' =>$request->date,
    ]);
    return response()->json([
        'status' =>true,
        'message' =>'task created successfully',
    
    ],200);
}

}
