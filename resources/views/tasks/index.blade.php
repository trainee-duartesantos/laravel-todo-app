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
        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
            <a href="/tasks"
            class="filter-btn {{ !$currentPriority ? 'active' : '' }}">Todas</a>

            <a href="/tasks?priority=low"
            class="filter-btn {{ $currentPriority === 'low' ? 'active' : '' }}">Baixa</a>

            <a href="/tasks?priority=medium"
            class="filter-btn {{ $currentPriority === 'medium' ? 'active' : '' }}">Média</a>

            <a href="/tasks?priority=high"
            class="filter-btn {{ $currentPriority === 'high' ? 'active' : '' }}">Alta</a>
        </div>

        {{-- Data --}}
        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
            <a href="/tasks"
            class="filter-btn {{ !$currentDue ? 'active' : '' }}">Todas</a>

            <a href="/tasks?due=overdue"
            class="filter-btn {{ $currentDue === 'overdue' ? 'active' : '' }}">Atrasadas</a>

            <a href="/tasks?due=today"
            class="filter-btn {{ $currentDue === 'today' ? 'active' : '' }}">Hoje</a>

            <a href="/tasks?due=future"
            class="filter-btn {{ $currentDue === 'future' ? 'active' : '' }}">Futuras</a>
        </div>

    </div>


    {{-- Lista --}}
    <ul class="space-y-2">
        @foreach ($tasks as $task)
            <li class="grid grid-cols-[1fr_auto_auto_auto] items-center gap-3 bg-gray-50 px-4 py-2 rounded">
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

                <div class="flex gap-2">
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

</body>
</html>
