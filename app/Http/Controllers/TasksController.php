<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;  //追加

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->get();
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            
            return view('tasks.index', [
                'tasks' => $tasks,
            ]);
        }
        
        return view('welcome', $data);
    }

    
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[
            'task' =>$task,
            ]);
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'status'=>'required|max:191',
            'content' => 'required|max:191',
        ]);
        
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = \Auth::id();
        $task->save();

        return redirect('/');
    }

   
    public function show($id)
    {
        $task = Task::find($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
        ]);
        }
        
        return redirect('/');
    }

   
    public function edit($id)
    {
        $task = Task::find($id);
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task,
        ]);
        }
        
        return redirect('/');
        
    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status'=>'required|max:191',
            'content' => 'required|max:191',
        ]);
        
        $task = Task::find($id);
        if (\Auth::id() === $task->user_id) {
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }
        
        return redirect('/');
    }

    
    public function destroy($id)
    {
        $task = Task::find($id);
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        return redirect('/');
    }
}
