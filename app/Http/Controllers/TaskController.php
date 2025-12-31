<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepositoryInterface $tasks
    ) {}

    public function index()
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $data['status'] = TaskStatus::PENDING;

        $this->tasks->create($data);

        return redirect()->back();
    }

    public function destroy(int $id)
    {
        $task = $this->tasks->find($id);

        abort_if(! $task, 404);

        $this->tasks->delete($task);

        return redirect()->back();
    }
    public function toggle(int $id)
    {
        $this->tasks->toggle($id);

        return redirect()->back();
    }

}
