<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function create_user(Request $request)
    {
    
        $request->validate([
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
            'image'=> 'mimes:jpeg,jpg,png,gif|max:2048', 
        ]);
        $data = $request->all();
        $path = 'asset/storage/images/'.$data['image'];
        $fileName=time().$request->file('image')->getClientoriginalName();
        $path=$request->file('image')->storeAs('images',$fileName,'public');
        $datas["image"]='/storage/'.$path;        
        $data['image']=$fileName; 
        $data['password']=bcrypt($request->password);
       $user =  User::create($data);
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
        'user_id'=>'required',
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
public function view_task()
{
    $user =Auth::user();


    $id=$user->id;
    

    $task = Task::where('user_id','=',$id)->get();
    return response()->json($task);
}


public function update_task(Request $request, $taskId)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'date' => 'required|date',
    ]);

    // Find the task by ID
    $task = Task::find($taskId);

    // Check if the task exists
    if (!$task) {
        return response()->json([
            'status' => false,
            'message' => 'Task not found',
        ], 404);
    }

    // Update task attributes
    $task->title = $request->title;
    $task->description = $request->description;
    $task->date = $request->date;

    // Save the updated task
    $task->update();

    return response()->json([
        'status' => true,
        'message' => 'Task updated successfully',
        'task' => $task, // Optionally, you can return the updated task data
    ], 200);
}

public function delete_task($taskId)
{
$task =Task::find($taskId)->delete();
return response()->json([
    'status' =>true,
    'message' =>'task deleted successfully',
]);
}

public function select($taskId)
{
    $id =Task::find($taskId);
    $hh = $id->task_id;
    $task=Task::where('task_id','=',$hh)->first();
    return response()->json($task);
}

public function view_tasks()
{
    $tasks = DB::table('users')
    ->join('tasks', 'users.id', '=', 'tasks.user_id')   
    ->select('users.name','tasks.*')
    ->get();
    return response()->json($tasks);
}

}
