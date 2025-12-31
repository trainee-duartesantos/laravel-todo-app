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

    public function index(Request $request)
    {
        $status = $request->query('status');

        return view('tasks.index', [
            'tasks' => $this->tasks->all(
                $status ? TaskStatus::from($status) : null
            ),
            'currentStatus' => $status,
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
