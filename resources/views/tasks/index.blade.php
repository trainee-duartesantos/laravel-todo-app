<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto mt-16 bg-white shadow rounded-lg p-6 space-y-6">
    <h1 class="text-2xl font-bold text-center">
        📝 To-Do List
    </h1>

    {{-- Form adicionar --}}
    <form method="POST" action="/tasks" class="flex flex-wrap gap-2">
        @csrf
        <input
            type="text"
            name="title"
            placeholder="Nova tarefa…"
            class="flex-1 min-w-[200px] border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            required
        >

        <select
            name="priority"
            class="border rounded px-3 py-2 w-[110px]"
        >
            <option value="low">Baixa</option>
            <option value="medium" selected>Média</option>
            <option value="high">Alta</option>
        </select>

        <input
            type="date"
            name="due_date"
            class="border rounded px-3 py-2 w-[110px]"
        />

        <button
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap"
        >
            Adicionar
        </button>
    </form>

    <div class="flex justify-center gap-2 flex-wrap">

        {{-- Estado --}}
        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
            <a href="/tasks"
            class="filter-btn {{ !$currentStatus ? 'active' : '' }}">Todas</a>

            <a href="/tasks?status=pending"
            class="filter-btn {{ $currentStatus === 'pending' ? 'active' : '' }}">Pendentes</a>

            <a href="/tasks?status=completed"
            class="filter-btn {{ $currentStatus === 'completed' ? 'active' : '' }}">Concluídas</a>
        </div>

        {{-- Prioridade --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-center gap-2 flex-wrap">
                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'due' => $currentDue,
                ])) }}"
                class="filter-btn {{ !$currentPriority ? 'active' : '' }}">
                    Todas
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => 'low',
                    'due' => $currentDue,
                ])) }}"
                class="filter-btn {{ $currentPriority === 'low' ? 'active' : '' }}">
                    Baixa
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => 'medium',
                    'due' => $currentDue,
                ])) }}"
                class="filter-btn {{ $currentPriority === 'medium' ? 'active' : '' }}">
                    Média
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => 'high',
                    'due' => $currentDue,
                ])) }}"
                class="filter-btn {{ $currentPriority === 'high' ? 'active' : '' }}">
                    Alta
                </a>
            </div>
        </div>


        {{-- Data --}}
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-center gap-2 flex-wrap">
                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => $currentPriority,
                ])) }}"
                class="filter-btn {{ !$currentDue ? 'active' : '' }}">
                    Todas
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => $currentPriority,
                    'due' => 'overdue',
                ])) }}"
                class="filter-btn {{ $currentDue === 'overdue' ? 'active' : '' }}">
                    Atrasadas
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => $currentPriority,
                    'due' => 'today',
                ])) }}"
                class="filter-btn {{ $currentDue === 'today' ? 'active' : '' }}">
                    Hoje
                </a>

                <a href="{{ route('tasks.index', array_filter([
                    'status' => $currentStatus,
                    'priority' => $currentPriority,
                    'due' => 'future',
                ])) }}"
                class="filter-btn {{ $currentDue === 'future' ? 'active' : '' }}">
                    Futuras
                </a>
            </div>
        </div>

    </div>


    {{-- Lista --}}
    <ul class="space-y-2">
        @foreach ($tasks as $task)
            <li
                class="grid grid-cols-[1fr_auto_auto_auto] items-center gap-3 bg-gray-50 px-4 py-2 rounded cursor-pointer hover:bg-gray-100"
                onclick="openModal(
                    '{{ $task->title }}',
                    '{{ $task->status->value }}',
                    '{{ $task->priority->label() }}',
                    '{{ $task->due_date?->format('d/m/Y') ?? '—' }}'
                )"
            >

                <span class="{{ $task->completed ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </span>

                <span class="text-sm px-2 py-1 rounded {{ $task->priority->color() }}">
                    {{ $task->priority->label() }}
                </span>

                @if ($task->dueStatus()->value !== 'none')
                    <span class="text-xs px-2 py-1 rounded {{ $task->dueStatus()->color() }}">
                        {{ $task->dueStatus()->label() }}
                    </span>
                @endif

                <div class="flex gap-2" onclick="event.stopPropagation()">
                    <form method="POST" action="/tasks/{{ $task->id }}/toggle">
                        @csrf
                        @method('PATCH')

                        <button class="text-sm px-2 py-1 border rounded">
                            {{ $task->isCompleted() ? '↩️ Reabrir' : '✅ Concluir' }}
                        </button>
                    </form>

                    <form method="POST" action="/tasks/{{ $task->id }}">
                        @csrf
                        @method('DELETE')

                        <button class="text-sm px-2 py-1 border rounded text-red-500">
                            🗑
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>

    {{-- Modal --}}
    <div
        id="task-modal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
            <button
                onclick="closeModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black"
            >
                ✕
            </button>

            <h2 id="modal-title" class="text-xl font-bold mb-4"></h2>

            <div class="space-y-2 text-sm">
                <p><strong>Estado:</strong> <span id="modal-status"></span></p>
                <p><strong>Prioridade:</strong> <span id="modal-priority"></span></p>
                <p><strong>Data limite:</strong> <span id="modal-due"></span></p>
            </div>
        </div>
    </div>

</body>
</html>
