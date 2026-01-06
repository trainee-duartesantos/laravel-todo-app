<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TaskDueStatus;
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
            $dueEnum = $due ? TaskDueStatus::tryFrom($due) : null;

            return view('tasks.index', [
                'tasks' => $this->tasks->all($statusEnum, $priorityEnum, $dueEnum),
                'currentStatus' => $status,
                'currentPriority' => $priority,
                'currentDue' => $due,
            ]);
        }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => 'nullable|string',
            'priority' => ['required'],
            'due_date' => ['nullable', 'date'],
        ]);

        $data['status'] = TaskStatus::PENDING;
        $data['completed'] = false;

        $this->tasks->create($data);

        return redirect()->route(
            'tasks.index',
            $request->except('_token', '_method')
        );

    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = $this->tasks->find($id);
        $this->tasks->update($task, $validated);

        // 🔥 Resposta JSON para Vue
        if ($request->expectsJson()) {
            $task = $task->fresh();

            return response()->json([
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status->value,
                    'priority' => $task->priority->label(),
                    'priority_key' => $task->priority->value,
                    'due' => $task->due_date
                        ? $task->due_date->format('d/m/Y')
                        : '—',
                    'due_raw' => $task->due_date?->format('Y-m-d') ?? '',
                ],
            ]);
        }

        // 🧭 Fallback HTML
        return redirect()->route('tasks.index', $request->query());
    }

    public function destroy(Request $request, int $id)
    {
        $task = $this->tasks->find($id);
        $this->tasks->delete($task);

        return redirect()->route(
            'tasks.index',
            $request->except('_token', '_method')
        );
    }

    public function toggle(Request $request, int $id)
    {
        $task = $this->tasks->find($id);
        $this->tasks->toggle($task);

        return redirect()->route(
            'tasks.index',
            $request->except('_token', '_method')
        );
    }
}
