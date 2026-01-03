<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>To-Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 min-h-screen flex justify-center">
    <div id="app" class="w-full">
        <div class="max-w-xl mx-auto mt-16 bg-white shadow rounded-lg p-6 space-y-6">

            <h1 class="text-2xl font-bold text-center">
                📝 Lista de Tarefas
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

                <select name="priority" class="border rounded px-3 py-2 w-[120px]">
                    <option value="prioridade" selected>Prioridade</option>
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>

                <input
                    type="date"
                    name="due_date"
                    class="border rounded px-3 py-2 w-[150px]"
                >
                
                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Adicionar
                </button>
            </form>

            <form method="GET" action="/tasks" class="flex flex-wrap gap-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">Estado</option>
                    @foreach (\App\Enums\TaskStatus::cases() as $s)
                        <option
                            value="{{ $s->value }}"
                            @selected($currentStatus === $s->value)
                        >
                            {{ ucfirst($s->value) }}
                        </option>
                    @endforeach
                </select>

                <select name="priority" class="border rounded px-3 py-2">
                    <option value="">Prioridade</option>
                    @foreach (\App\Enums\TaskPriority::cases() as $p)
                        <option
                            value="{{ $p->value }}"
                            @selected($currentPriority === $p->value)
                        >
                            {{ $p->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="due" class="border rounded px-3 py-2">
                    <option value="">Data</option>
                    @foreach (\App\Enums\TaskDueStatus::cases() as $d)
                        <option
                            value="{{ $d->value }}"
                            @selected($currentDue === $d->value)
                        >
                            {{ ucfirst($d->value) }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-gray-200 px-4 py-2 rounded">
                    Filtrar
                </button>

                <a href="/tasks" class="text-sm px-3 py-2 border rounded">
                    Limpar
                </a>
            </form>


            {{-- Lista --}}
            <ul class="space-y-2">
                @foreach ($tasks as $task)
                    @php
                        $taskForModal = json_encode([
                            'id' => $task->id,
                            'title' => $task->title,
                            'status' => $task->status->value,
                            'priority' => $task->priority->label(),
                            'priority_key' => $task->priority->value,
                            'due' => $task->due_date
                                ? $task->due_date->format('d/m/Y')
                                : '—',
                            'due_raw' => $task->due_date?->format('Y-m-d') ?? '',
                        ]);
                    @endphp

                    <li
                        class="grid grid-cols-[1fr_auto_auto_auto] items-center gap-3
                            bg-gray-50 px-4 py-2 rounded cursor-pointer hover:bg-gray-100"
                        data-task='{{ $taskForModal }}'
                        @click="openFromElement($event)"
                    >

                        <span>{{ $task->title }}</span>

                        <span class="text-sm px-2 py-1 rounded {{ $task->priority->color() }}">
                            {{ $task->priority->label() }}
                        </span>

                        <div class="flex gap-2" @click.stop>
                            <form method="POST" action="/tasks/{{ $task->id }}/toggle">
                                @csrf
                                @method('PATCH')

                                @foreach(request()->query() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <button class="text-sm px-2 py-1 border rounded">
                                    {{ $task->isCompleted() ? '↩️ Reabrir' : '✅ Concluir' }}
                                </button>
                            </form>


                                                        <form method="POST" action="/tasks/{{ $task->id }}">
                                @csrf
                                @method('DELETE')

                                @foreach(request()->query() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <button class="text-sm px-2 py-1 border rounded text-red-500">
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
            @toggle="toggleFromModal"
            @delete="deleteFromModal"
            @save="saveFromModal"
        />


        <form
            ref="toggleForm"
            method="POST"
            :action="`/tasks/${actionTaskId}/toggle`"
            style="display:none"
        >
            @csrf
            @method('PATCH')
        </form>

        <form
            ref="deleteForm"
            method="POST"
            :action="`/tasks/${actionTaskId}`"
            style="display:none"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>
</body>
</html>
