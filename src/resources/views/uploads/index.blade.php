@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Browse Resources</h1>
        <p class="mt-2 text-gray-600">Search and download study materials shared by students and teachers</p>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <form method="GET" action="{{ route('uploads.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                <select name="branch_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <select name="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="upload_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Types</option>
                    <option value="question_paper" {{ request('upload_type') == 'question_paper' ? 'selected' : '' }}>Question Paper</option>
                    <option value="notes" {{ request('upload_type') == 'notes' ? 'selected' : '' }}>Notes</option>
                    <option value="assignment" {{ request('upload_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    <option value="mst" {{ request('upload_type') == 'mst' ? 'selected' : '' }}>MST</option>
                    <option value="other" {{ request('upload_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2">
                <a href="{{ route('uploads.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Clear
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">{{ $uploads->total() }} Resources Found</h2>
                <a href="{{ route('uploads.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                    Upload New
                </a>
            </div>

            <div class="space-y-3">
                @forelse($uploads as $upload)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $upload->file_name }}</h3>
                                <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ $upload->subject->name }}</span>
                                    <span>•</span>
                                    <span>{{ ucfirst(str_replace('_', ' ', $upload->upload_type)) }}</span>
                                    <span>•</span>
                                    <span>{{ $upload->branch->code }} Year {{ $upload->year }}</span>
                                    @if($upload->teacher_name)
                                        <span>•</span>
                                        <span>{{ $upload->teacher_name }}</span>
                                    @endif
                                </div>
                                <div class="mt-1 text-xs text-gray-400">
                                    Uploaded {{ $upload->created_at->diffForHumans() }} by {{ $upload->user->name }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                <span class="font-medium">{{ $upload->downloads_count }}</span> downloads
                            </div>
                            <a href="{{ route('uploads.download', $upload) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No resources found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or upload a new resource.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $uploads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection