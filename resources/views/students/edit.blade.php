@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white">Edit Student</h2>
        <p class="text-gray-400 mt-1">Update student information</p>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-8">
        <form method="POST" action="/students/{{ $student->id }}">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                        class="w-full bg-gray-800 border border-gray-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition">
                    @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}"
                        class="w-full bg-gray-800 border border-gray-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Course</label>
                    <select name="course" class="w-full bg-gray-800 border border-gray-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition">
                        @foreach(['OOP', 'Web Dev', 'Database', 'AI', 'Networks', 'Math'] as $course)
                            <option value="{{ $course }}" {{ $student->course == $course ? 'selected' : '' }}>{{ $course }}</option>
                        @endforeach
                    </select>
                    @error('course')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-4 pt-2">
                    <button type="submit" class="btn-primary px-8 py-3 rounded-xl font-semibold text-white transition">
                        Update Student
                    </button>
                    <a href="/students" class="bg-gray-700 hover:bg-gray-600 px-8 py-3 rounded-xl font-semibold text-white transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection