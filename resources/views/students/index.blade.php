@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-3xl font-bold text-white">Students</h2>
        <p class="text-gray-400 mt-1">Manage all enrolled students</p>
    </div>
    @if(auth()->user()->role === 'admin')
    <a href="/students/create" class="btn-primary px-6 py-3 rounded-xl font-semibold text-white transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Student
    </a>
    @endif
</div>

<!-- Search -->
<div class="bg-gray-900 border border-gray-700 rounded-2xl p-4 mb-6">
    <input type="text" id="searchInput" placeholder="🔍 Search by name, email or course..." value="{{ $search }}"
        class="w-full bg-transparent text-white placeholder-gray-500 outline-none text-sm">
</div>

<!-- Table -->
<div class="bg-gray-900 border border-gray-700 rounded-2xl overflow-hidden">
    <table class="w-full text-sm" id="studentsTable">
        <thead class="bg-gray-800">
            <tr class="text-gray-400">
                <th class="text-left px-6 py-4">#</th>
                <th class="text-left px-6 py-4">Name</th>
                <th class="text-left px-6 py-4">Email</th>
                <th class="text-left px-6 py-4">Course</th>
                @if(auth()->user()->role === 'admin')
                <th class="text-left px-6 py-4">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody id="studentsBody">
            @foreach($students as $i => $student)
            <tr class="border-t border-gray-800 hover:bg-gray-800/50 transition">
                <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                <td class="px-6 py-4 text-white font-medium">{{ $student->name }}</td>
                <td class="px-6 py-4 text-gray-400">{{ $student->email }}</td>
                <td class="px-6 py-4"><span class="bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full text-xs">{{ $student->course }}</span></td>
                @if(auth()->user()->role === 'admin')
                <td class="px-6 py-4 flex gap-2">
                    <a href="/students/{{ $student->id }}/edit" class="bg-yellow-500/20 text-yellow-300 hover:bg-yellow-500/40 px-3 py-1 rounded-lg text-xs transition">Edit</a>
                    <form method="POST" action="/students/{{ $student->id }}" onsubmit="return confirm('Delete this student?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-500/20 text-red-300 hover:bg-red-500/40 px-3 py-1 rounded-lg text-xs transition">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $students->links() }}</div>
</div>

<script>
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`/students?search=${this.value}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            let html = '';
            data.data.forEach((s, i) => {
                html += `<tr class="border-t border-gray-800 hover:bg-gray-800/50 transition">
                    <td class="px-6 py-4 text-gray-500">${i+1}</td>
                    <td class="px-6 py-4 text-white font-medium">${s.name}</td>
                    <td class="px-6 py-4 text-gray-400">${s.email}</td>
                    <td class="px-6 py-4"><span class="bg-indigo-500/20 text-indigo-300 px-3 py-1 rounded-full text-xs">${s.course}</span></td>
                </tr>`;
            });
            document.getElementById('studentsBody').innerHTML = html;
        });
    }, 400);
});
</script>
@endsection