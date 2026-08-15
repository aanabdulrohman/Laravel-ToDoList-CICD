<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <div class="max-w-xl w-full mx-auto px-4 py-10">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-lg mb-3">
                <i class="fa-solid fa-check-double text-xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Task Manager</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola tugas harian kamu dengan lebih terstruktur -bruno-</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Form Input -->
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <form action="/tasks" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="title" placeholder="Ketikkan tugas baru..." value="{{ old('title') }}" required
                            class="flex-1 bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm placeholder:text-slate-400">
                        <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center gap-2 shrink-0">
                            <i class="fa-solid fa-plus text-xs"></i> Tambah
                        </button>
                    </div>

                    @error('title')
                        <p class="text-red-500 text-xs font-medium pl-1">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            <!-- Stats Bar -->
            <div class="px-6 py-3 bg-slate-100/70 border-b border-slate-200/60 flex items-center justify-between text-xs font-medium text-slate-500">
                <span>Total: <strong class="text-slate-800 font-semibold">{{ $tasks->count() }}</strong></span>
                <span>Selesai: <strong class="text-slate-800 font-semibold">{{ $tasks->where('is_completed', true)->count() }}</strong></span>
            </div>

            <!-- Task List -->
            <ul class="divide-y divide-slate-100">
                @forelse ($tasks as $task)
                    <li class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition group">
                        
                        <!-- Toggle Status -->
                        <form action="/tasks/{{ $task->id }}" method="POST" class="flex items-center gap-3 flex-1 min-w-0 pr-4">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex items-center gap-3 text-left w-full group/btn focus:outline-none">
                                <div class="w-5 h-5 rounded-md border flex items-center justify-center transition shrink-0
                                    {{ $task->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 group-hover/btn:border-indigo-500' }}">
                                    @if ($task->is_completed)
                                        <i class="fa-solid fa-check text-xs"></i>
                                    @endif
                                </div>
                                <span class="text-sm truncate font-medium transition
                                    {{ $task->is_completed ? 'line-through text-slate-400' : 'text-slate-700 group-hover/btn:text-indigo-600' }}">
                                    {{ $task->title }}
                                </span>
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <form action="/tasks/{{ $task->id }}" method="POST" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus tugas ini?')"
                                class="text-slate-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition opacity-0 group-hover:opacity-100 focus:opacity-100"
                                title="Hapus Tugas">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </form>

                    </li>
                @empty
                    <li class="p-8 text-center text-slate-400 text-sm">
                        <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                        Belum ada tugas tersimpan.
                    </li>
                @endforelse
            </ul>

        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="py-4 text-center text-xs text-slate-400">
        Laravel Task Manager App
    </footer>

</body>
</html>