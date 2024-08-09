<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Response;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        //$tasks = Task::all();
		//$tasks = Task::where('is_completed', false)->get();
		
		// Check if the "display_all" query parameter is set
		$displayAll = $request->query('display_all', false);

		// If display_all is true, retrieve all tasks; otherwise, retrieve only pending tasks
		if ($displayAll) {
			$tasks = Task::all();
		} else {
			$tasks = Task::where('is_completed', false)->get();
		}
		
            return view('tasks.ajax', compact('tasks', 'displayAll'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title',
            'description' => 'nullable|string',
        ]);

        $task = Task::create($request->except('_token'));

        return Response::json($task);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title',
            'description' => 'nullable|string',
        ]);

        $task->update($request->except('_token'));

        return Response::json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return Response::json(['success' => 'Task deleted successfully']);
    }
	
	public function complete(Task $task)
	{
		$task->is_completed = true;
		$task->save();

		return Response::json(['success' => 'Task marked as completed']);
	}
}
