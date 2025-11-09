@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Subject</h1>
        <p class="mt-2 text-gray-600">Update subject information</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <!-- Branch Selection -->
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('branch_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $subject->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }} ({{ $branch->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year and Semester -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            Year <span class="text-red-500">*</span>
                        </label>
                        <select id="year" name="year" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('year') border-red-500 @enderror">
                            <option value="">Select Year</option>
                            <option value="1" {{ old('year', $subject->year) == 1 ? 'selected' : '' }}>1st Year</option>
                            <option value="2" {{ old('year', $subject->year) == 2 ? 'selected' : '' }}>2nd Year</option>
                            <option value="3" {{ old('year', $subject->year) == 3 ? 'selected' : '' }}>3rd Year</option>
                            <option value="4" {{ old('year', $subject->year) == 4 ? 'selected' : '' }}>4th Year</option>
                        </select>
                        @error('year')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                            Semester (Optional)
                        </label>
                        <select id="semester" name="semester"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Select Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester', $subject->semester) == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Subject Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject Code (Optional)
                    </label>
                    <input type="text" id="code" name="code" value="{{ old('code', $subject->code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="e.g., CS101, IT201">
                    <p class="mt-1 text-xs text-gray-500">Internal subject code for reference</p>
                </div>

                <!-- Subject Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $subject->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., Data Structures and Algorithms">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statistics -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Subject Statistics</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Total Uploads:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $subject->uploads->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Approved Uploads:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $subject->uploads->where('status', 'approved')->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $subject->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $subject->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Subject is active and visible to users
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.subjects.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Update Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection