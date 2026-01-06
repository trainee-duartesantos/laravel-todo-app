<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 min-h-screen">
    <div id="app" class="w-full">
        <div class="mx-auto w-full max-w-7xl px-3 sm:px-6 lg:px-8 mt-6 sm:mt-10">
            
            <div class="bg-white shadow rounded-xl p-4 sm:p-6 space-y-6">
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between border-b sm:border-b-0 pb-3 sm:pb-0">

                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        📝 Lista de Tarefas
                    </h1>

                    @if (session('welcome'))
                        <div
                            id="welcome-flash"
                            class="mb-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 flex items-center gap-2
                                animate-[fadeIn_0.3s_ease-out]"
                        >
                            <span>👋</span>
                            <span>{{ session('welcome') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-start sm:justify-end">

                        @auth
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="text-sm text-gray-600 hover:text-gray-900">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Entrar
                            </a>
                        @endguest
                    </div>
                </div>


            {{-- Form adicionar --}}
            <form method="POST" action="/tasks" class="flex flex-col sm:flex-row gap-2">
                @csrf

                <label for="title" class="sr-only">Título da tarefa</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    placeholder="Nova tarefa…"
                    class="w-full sm:flex-1 border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                    required
                />

                <select id="priority" name="priority" class="w-full sm:w-[120px] border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                    <option value="prioridade" selected>Prioridade</option>
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>

                <label for="due_date" class="sr-only">Data</label>
                <input
                    id="due_date"
                    type="date"
                    name="due_date"
                    class="w-full sm:w-[150px] border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                >
                
                <button class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded 
                                transition
                                hover:shadow-sm
                                active:scale-95
                                focus:outline-none focus:ring focus:ring-blue-300
                ">
                    Adicionar
                </button>
            </form>

            <form method="GET" action="/tasks" class="flex flex-col sm:flex-row gap-2">
                <select name="status" class="w-full sm:w-auto border rounded px-3 py-2">
                    <option value="">Estado</option>
                    @foreach (\App\Enums\TaskStatus::cases() as $status)
                        <option
                            value="{{ $status->value }}"
                            @selected($currentStatus === $status->value)
                        >
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>


                <select name="priority" class="w-full sm:w-auto border rounded px-3 py-2">
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

                <select name="due" class="w-full sm:w-auto border rounded px-3 py-2">
                    <option value="">Data</option>
                    @foreach (\App\Enums\TaskDueStatus::cases() as $due)
                        <option
                            value="{{ $due->value }}"
                            @selected($currentDue === $due->value)
                        >
                            {{ $due->label() }}
                        </option>
                    @endforeach
                </select>


                <button class="w-full sm:w-auto
                                text-sm px-2 py-1
                                border rounded
                                transition hover:shadow-sm
                                active:scale-95
                                focus:outline-none
                                focus:ring
                                focus:ring-blue-300"
                >
                    Filtrar
                </button>

                <a href="/tasks" class="w-full sm:w-auto text-center text-sm px-3 py-2 border rounded">
                    Limpar
                </a>
            </form>


            {{-- Lista --}}
            <ul class="space-y-2">
                @forelse ($tasks as $task)
                    @php
                        $taskForModal = json_encode([
                            'id' => $task->id,
                            'title' => $task->title,
                            'description' => $task->description,
                            'status' => $task->status->label(),
                            'priority' => $task->priority->label(),
                            'priority_key' => $task->priority->value,
                            'due' => $task->due_date
                                ? $task->due_date->format('d/m/Y')
                                : '—',
                            'due_raw' => $task->due_date?->format('Y-m-d') ?? '',
                        ]);

                        $dueStatus = $task->dueStatus();
                    @endphp

                    <li
                        class="
                            flex flex-col sm:flex-row
                            sm:items-center sm:justify-between
                            gap-3
                            px-4 py-3 rounded-lg
                            cursor-pointer
                            transition
                            hover:scale-[1.01]
                            active:scale-[0.99]
                            hover:shadow-sm hover:bg-gray-50
                            {{ $task->isCompleted() 
                            ? 'bg-gray-50 opacity-70 border-l-4 border-green-400 line-through' 
                            : 'bg-white border-l-4 border-yellow-400' }}
                        "
                        data-task='{{ $taskForModal }}'
                        onclick="openFromElement(event)"
                    >
                        <div class="min-w-0">
                            <p class="font-medium truncate">
                                {{ $task->title }}
                            </p>

                            <div class="mt-1 flex flex-wrap gap-2 text-sm">
                                

                                {{-- Estado --}}
                                @if ($task->isCompleted())
                                    <span class="px-2 py-0.5 rounded bg-green-100 text-green-700">
                                        Concluída
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-700">
                                        Pendente
                                    </span>
                                @endif

                                {{-- Prioridade --}}
                                <span class="px-2 py-0.5 rounded {{ $task->priority->color() }}">
                                    {{ $task->priority->label() }}
                                </span>

                                {{-- Data --}}
                                @if ($dueStatus !== \App\Enums\TaskDueStatus::NONE)
                                    <span class="px-2 py-0.5 rounded {{ $dueStatus->color() }}">
                                        {{ $dueStatus->label() }}
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                            <form method="POST" action="/tasks/{{ $task->id }}/toggle">
                                @csrf
                                @method('PATCH')

                                @foreach(request()->query() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <button class="flex-1 sm:flex-none
                                                text-sm px-2 py-1
                                                border rounded
                                                cursor-pointer
                                                transition hover:shadow-sm
                                                active:scale-95
                                                focus:outline-none
                                                focus:ring
                                                focus:ring-blue-300">
                                    {{ $task->isCompleted() ? '↩️ Reabrir' : '✅ Concluir' }}
                                </button>
                            </form>


                                                        <form method="POST" action="/tasks/{{ $task->id }}">
                                @csrf
                                @method('DELETE')

                                @foreach(request()->query() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <button class="flex-1 sm:flex-none
                                                text-sm px-2 py-1
                                                border rounded
                                                cursor-pointer
                                                transition
                                                hover:bg-red-50
                                                active:scale-95
                                                focus:outline-none
                                                focus:ring
                                                focus:ring-blue-300"
                                                aria-label="Eliminar tarefa"
                                                title="Eliminar tarefa"
                                >
                                    🗑
                                </button>
                            </form>
                        </div>

                    </li>

                    @empty
                    {{-- EMPTY STATE --}}
                    <li class="py-12">
                        <div class="text-center text-gray-500 space-y-3
                                    animate-[fadeUp_0.4s_ease-out]"
                        >
                            <div class="text-4xl">🗒️</div>

                            <p class="text-lg font-medium text-gray-700">
                                Nenhuma tarefa encontrada
                            </p>

                            @if(request()->hasAny(['status', 'priority', 'due']))
                                <p class="text-sm">
                                    Tenta ajustar ou limpar os filtros aplicados.
                                </p>

                                <a
                                    href="{{ route('tasks.index') }}"
                                    class="inline-block mt-2 text-sm text-blue-600 hover:underline"
                                >
                                    Limpar filtros
                                </a>
                            @else
                                <p class="text-sm">
                                    Começa por adicionar a tua primeira tarefa 👆
                                </p>
                            @endif
                        </div>
                    </li>

                @endforelse
            </ul>
        </div>

        {{-- Modal Vue --}}
        <div id="task-modal-root"></div>

        <form id="toggle-form" method="POST" style="display:none">
            @csrf
            @method('PATCH')
        </form>

        <form id="delete-form" method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>

</body>
</html>
