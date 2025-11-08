@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Uploads</h1>
            <p class="mt-2 text-gray-600">Track and manage your uploaded resources</p>
        </div>
        <a href="{{ route('uploads.create') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition inline-flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Upload New
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" x-data="{ tab: 'all' }">
                <button @click="tab = 'all'" 
                        :class="tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    All ({{ $uploads->total() }})
                </button>
                <button @click="tab = 'approved'" 
                        :class="tab === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Approved ({{ $uploads->where('status', 'approved')->count() }})
                </button>
                <button @click="tab = 'pending'" 
                        :class="tab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Pending ({{ $uploads->where('status', 'pending')->count() }})
                </button>
                <button @click="tab = 'rejected'" 
                        :class="tab === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Rejected ({{ $uploads->where('status', 'rejected')->count() }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Uploads List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <div class="space-y-4">
                @forelse($uploads as $upload)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- File Icon -->
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-lg flex items-center justify-center
                                                {{ $upload->status === 'approved' ? 'bg-green-100' : '' }}
                                                {{ $upload->status === 'pending' ? 'bg-yellow-100' : '' }}
                                                {{ $upload->status === 'rejected' ? 'bg-red-100' : '' }}">
                                        <svg class="h-6 w-6 
                                                    {{ $upload->status === 'approved' ? 'text-green-600' : '' }}
                                                    {{ $upload->status === 'pending' ? 'text-yellow-600' : '' }}
                                                    {{ $upload->status === 'rejected' ? 'text-red-600' : '' }}"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Upload Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $upload->file_name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                     {{ $upload->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                     {{ $upload->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                     {{ $upload->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($upload->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $upload->subject->name }}</span>
                                        <span>•</span>
                                        <span>{{ ucfirst(str_replace('_', ' ', $upload->upload_type)) }}</span>
                                        <span>•</span>
                                        <span>{{ $upload->branch->code }} Year {{ $upload->year }}</span>
                                        <span>•</span>
                                        <span>{{ $upload->created_at->diffForHumans() }}</span>
                                    </div>

                                    @if($upload->description)
                                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($upload->description, 100) }}</p>
                                    @endif

                                    @if($upload->admin_comment && $upload->status === 'rejected')
                                        <div class="mt-2 p-2 bg-red-50 border-l-4 border-red-400 rounded">
                                            <p class="text-sm text-red-700">
                                                <span class="font-medium">Rejection Reason:</span> {{ $upload->admin_comment }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($upload->status === 'approved')
                                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                {{ $upload->downloads_count }} downloads
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                @if($upload->status === 'approved')
                                    <a href="{{ route('uploads.download', $upload) }}" 
                                       class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                       title="Download">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('uploads.destroy', $upload) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this upload?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No uploads yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by uploading a new resource.</p>
                        <div class="mt-6">
                            <a href="{{ route('uploads.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Upload Resource
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($uploads->hasPages())
                <div class="mt-6">
                    {{ $uploads->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection