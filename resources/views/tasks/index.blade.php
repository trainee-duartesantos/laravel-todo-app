<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex justify-center">
    <div id="app" class="w-full">
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
                    class="flex-1 min-w-[200px] border rounded px-3 py-2"
                    required
                >

                <select name="priority" class="border rounded px-3 py-2 w-[110px]">
                    <option value="low">Baixa</option>
                    <option value="medium" selected>Média</option>
                    <option value="high">Alta</option>
                </select>

                <input
                    type="date"
                    name="due_date"
                    class="border rounded px-3 py-2 w-[110px]"
                >

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Adicionar
                </button>
            </form>

            {{-- Lista --}}
            <ul class="space-y-2">
                @foreach ($tasks as $task)

                    @php
                        $taskForModal = json_encode([
                            'title'    => $task->title,
                            'status'   => $task->status->value,
                            'priority' => $task->priority->label(),
                            'due'      => $task->due_date
                                ? $task->due_date->format('d/m/Y')
                                : '—',
                        ]);
                    @endphp

                    <li
                        class="grid grid-cols-[1fr_auto_auto_auto] items-center gap-3
                            bg-gray-50 px-4 py-2 rounded cursor-pointer hover:bg-gray-100"
                        data-task='{{ $taskForModal }}'
                        @click="openFromElement"
                    >
                        <span>{{ $task->title }}</span>

                        <span class="text-sm px-2 py-1 rounded {{ $task->priority->color() }}">
                            {{ $task->priority->label() }}
                        </span>

                        <div class="flex gap-2" @click.stop>
                            <form method="POST" action="/tasks/{{ $task->id }}/toggle">
                                @csrf
                                @method('PATCH')
                                <button class="text-sm border px-2 py-1 rounded">
                                    {{ $task->isCompleted() ? '↩️ Reabrir' : '✅ Concluir' }}
                                </button>
                            </form>

                            <form method="POST" action="/tasks/{{ $task->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm border px-2 py-1 rounded text-red-500">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </li>

                @endforeach
            </ul>
        </div>

        {{-- Modal Vue --}}
        <task-modal
            :visible="modal.visible"
            :task="modal.task"
            @close="close"
        />
    </div>
</body>
</html>
