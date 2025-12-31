<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-xl mx-auto mt-16 bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">
        📝 To-Do List
    </h1>

    {{-- Form adicionar --}}
    <form method="POST" action="/tasks" class="flex gap-2 mb-6">
        @csrf
        <input
            type="text"
            name="title"
            placeholder="Nova tarefa…"
            class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            required
        >
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
            Adicionar
        </button>
    </form>

    <div class="flex justify-center gap-4 mb-6">
        <a href="/tasks"
        class="{{ empty($currentStatus) ? 'font-bold underline' : '' }}">
            Todas
        </a>

        <a href="/tasks?status=pending"
        class="{{ $currentStatus === 'pending' ? 'font-bold underline' : '' }}">
            Pendentes
        </a>

        <a href="/tasks?status=completed"
        class="{{ $currentStatus === 'completed' ? 'font-bold underline' : '' }}">
            Concluídas
        </a>
    </div>

    {{-- Lista --}}
    <ul class="space-y-2">
        @foreach ($tasks as $task)
            <li class="flex items-center justify-between bg-gray-50 px-4 py-2 rounded">
                <span class="{{ $task->completed ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </span>

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
