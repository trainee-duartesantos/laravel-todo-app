<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use App\Enums\TaskPriority;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepositoryInterface $tasks
    ) {}

    public function index(Request $request)
    {
        $status = $request->status
            ? TaskStatus::from($request->status)
            : null;
        
        $priority = $request->priority
            ? TaskPriority::from($request->priority)
            : null;

        return view('tasks.index', [
            'tasks' => $this->tasks->all($status, $priority),
            'currentStatus' => $request->status,
            'currentPriority' => $request->priority,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required'],
        ]);

        $data['status'] = TaskStatus::PENDING;
        $data['completed'] = false;

        $this->tasks->create($data);

        return redirect()->back();
    }

    public function destroy(int $id)
    {
        $task = $this->tasks->find($id);
        $this->tasks->delete($task);

        return redirect('/tasks');
    }

    public function toggle(int $id)
    {
        $task = $this->tasks->find($id);
        $this->tasks->toggle($task);

        return redirect('/tasks');
    }


}
