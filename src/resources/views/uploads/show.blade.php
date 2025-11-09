@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Upload Details Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 text-white">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                     {{ $upload->status === 'approved' ? 'bg-green-500 bg-opacity-30 text-green-100' : '' }}
                                     {{ $upload->status === 'pending' ? 'bg-yellow-500 bg-opacity-30 text-yellow-100' : '' }}
                                     {{ $upload->status === 'rejected' ? 'bg-red-500 bg-opacity-30 text-red-100' : '' }}">
                            {{ ucfirst($upload->status) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20">
                            {{ ucfirst(str_replace('_', ' ', $upload->upload_type)) }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold">{{ $upload->file_name }}</h1>
                    <p class="mt-2 text-indigo-100">{{ $upload->subject->name }}</p>
                </div>

                <!-- Download Button -->
                @if($upload->isApproved())
                    <a href="{{ route('uploads.download', $upload) }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition font-medium">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </a>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Uploaded By</h3>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium text-sm">{{ strtoupper(substr($upload->user->name, 0, 2)) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $upload->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($upload->user->role) }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Branch & Year</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $upload->branch->name }}</p>
                        <p class="text-xs text-gray-500">Year {{ $upload->year }}{{ $upload->semester ? ', Semester ' . $upload->semester : '' }}</p>
                    </div>

                    @if($upload->teacher_name)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Teacher</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $upload->teacher_name }}</p>
                        </div>
                    @endif

                    @if($upload->exam_year)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Exam Year</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $upload->exam_year }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">File Details</h3>
                        <div class="mt-1 space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Size:</span>
                                <span class="font-medium text-gray-900">{{ number_format($upload->file_size / 1024 / 1024, 2) }} MB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Format:</span>
                                <span class="font-medium text-gray-900 uppercase">{{ pathinfo($upload->file_name, PATHINFO_EXTENSION) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Downloads:</span>
                                <span class="font-medium text-gray-900">{{ $upload->downloads_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Uploaded</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $upload->created_at->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $upload->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Visibility</h3>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $upload->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $upload->is_public ? 'Public' : 'Private' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($upload->description)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $upload->description }}</p>
                </div>
            @endif

            <!-- Admin Comment -->
            @if($upload->admin_comment)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-{{ $upload->status === 'rejected' ? 'red' : 'blue' }}-50 border-l-4 border-{{ $upload->status === 'rejected' ? 'red' : 'blue' }}-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-{{ $upload->status === 'rejected' ? 'red' : 'blue' }}-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-{{ $upload->status === 'rejected' ? 'red' : 'blue' }}-800">
                                    {{ $upload->status === 'rejected' ? 'Rejection Reason' : 'Admin Comment' }}
                                </h3>
                                <p class="mt-2 text-sm text-{{ $upload->status === 'rejected' ? 'red' : 'blue' }}-700">
                                    {{ $upload->admin_comment }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approval History -->
            @if($upload->approvals->count() > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Approval History</h3>
                    <div class="space-y-3">
                        @foreach($upload->approvals as $approval)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full {{ $approval->action === 'approve' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                        @if($approval->action === 'approve')
                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ ucfirst($approval->action) }}d by {{ $approval->approver->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $approval->created_at->diffForHumans() }}</p>
                                    @if($approval->comment)
                                        <p class="mt-1 text-sm text-gray-600">{{ $approval->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-between">
                <div class="flex space-x-3">
                    @can('delete', $upload)
                        <form method="POST" action="{{ route('uploads.destroy', $upload) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this upload?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-red-700 bg-white hover:bg-red-50 transition">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    @endcan

                    @can('approve', $upload)
                        @if($upload->isPending())
                            <form method="POST" action="{{ route('approvals.approve', $upload) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-white bg-green-600 hover:bg-green-700 transition">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('approvals.reject', $upload) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-white bg-red-600 hover:bg-red-700 transition">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>

                @if($upload->isApproved())
                    <a href="{{ route('uploads.download', $upload) }}" 
                       class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download File
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Uploads -->
    @if($upload->isApproved())
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">More from {{ $upload->subject->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($upload->subject->uploads()->approved()->where('id', '!=', $upload->id)->limit(4)->get() as $relatedUpload)
                    <a href="{{ route('uploads.show', $relatedUpload) }}" 
                       class="block bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $relatedUpload->file_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $relatedUpload->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection