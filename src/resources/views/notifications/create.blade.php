@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Send Notification</h1>
        <p class="mt-2 text-gray-600">Broadcast messages to students in specific branches and years</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('notifications.store') }}" x-data="{ sendTo: 'branch' }">
            @csrf

            <!-- Send To -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Send To <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" x-model="sendTo" value="branch" name="send_type" checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Specific Branch & Year</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" x-model="sendTo" value="user" name="send_type"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Specific User</span>
                    </label>
                </div>
            </div>

            <!-- Branch & Year Selection -->
            <div x-show="sendTo === 'branch'" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id" :required="sendTo === 'branch'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                        Year <span class="text-red-500">*</span>
                    </label>
                    <select id="year" name="year" :required="sendTo === 'branch'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>
            </div>

            <!-- User Selection -->
            <div x-show="sendTo === 'user'" class="mb-6">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                    User Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="user_email" name="user_email" :required="sendTo === 'user'"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="user@ccet.ac.in">
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Type <span class="text-red-500">*</span>
                </label>
                <select id="type" name="type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="general">General</option>
                    <option value="announcement">Announcement</option>
                </select>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" required value="{{ old('title') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="Important: Exam Schedule Updated">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Body -->
            <div class="mb-6">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea id="body" name="body" rows="6" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Enter your message here...">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('notifications.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Send Notification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection