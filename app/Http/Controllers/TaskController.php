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
            $status = $request->query('status');
            $priority = $request->query('priority');
            $due = $request->query('due'); // overdue|today|future|null

            $statusEnum = $status ? TaskStatus::tryFrom($status) : null;
            $priorityEnum = $priority ? TaskPriority::tryFrom($priority) : null;

            return view('tasks.index', [
                'tasks' => $this->tasks->all($statusEnum, $priorityEnum, $due),
                'currentStatus' => $status,
                'currentPriority' => $priority,
                'currentDue' => $due,
            ]);
        }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required'],
            'due_date' => ['nullable', 'date'],
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
