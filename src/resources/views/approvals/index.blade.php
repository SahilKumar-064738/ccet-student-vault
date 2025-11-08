@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pending Approvals</h1>
        <p class="mt-2 text-gray-600">Review and approve/reject uploaded resources</p>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4" x-data="{ selectedUploads: [] }">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">
                    <span x-text="selectedUploads.length"></span> selected
                </span>
                <button @click="if(selectedUploads.length > 0 && confirm('Approve selected uploads?')) { document.getElementById('bulk-approve-form').submit(); }"
                        :disabled="selectedUploads.length === 0"
                        :class="selectedUploads.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                    Bulk Approve
                </button>
            </div>
            
            <div class="text-sm text-gray-600">
                {{ $uploads->total() }} pending uploads
            </div>
        </div>

        <form id="bulk-approve-form" method="POST" action="{{ route('approvals.bulk-approve') }}" class="hidden">
            @csrf
            <template x-for="id in selectedUploads">
                <input type="hidden" name="upload_ids[]" :value="id">
            </template>
        </form>
    </div>

    <!-- Uploads List -->
    <div class="space-y-4">
        @forelse($uploads as $upload)
            <div class="bg-white rounded-lg shadow-sm p-6" x-data="{ showDetails: false, showApprovalForm: false, showRejectForm: false }">
                <div class="flex items-start justify-between">
                    <!-- Checkbox -->
                    <div class="flex items-start space-x-4 flex-1">
                        <input type="checkbox" 
                               :value="'{{ $upload->id }}'"
                               @change="$event.target.checked ? selectedUploads.push('{{ $upload->id }}') : selectedUploads = selectedUploads.filter(id => id !== '{{ $upload->id }}')"
                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">

                        <!-- Upload Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-lg font-medium text-gray-900">{{ $upload->file_name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending Review
                                </span>
                            </div>

                            <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Uploaded by:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ $upload->user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Subject:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ $upload->subject->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Type:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $upload->upload_type)) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Branch/Year:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ $upload->branch->code }} - Year {{ $upload->year }}</span>
                                </div>
                                @if($upload->teacher_name)
                                <div>
                                    <span class="text-gray-500">Teacher:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ $upload->teacher_name }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-500">Uploaded:</span>
                                    <span class="ml-2 text-gray-900 font-medium">{{ $upload->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @if($upload->description)
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-700">{{ $upload->description }}</p>
                                </div>
                            @endif

                            <!-- Toggle Details -->
                            <button @click="showDetails = !showDetails" 
                                    class="mt-3 text-sm text-indigo-600 hover:text-indigo-500">
                                <span x-show="!showDetails">Show file details</span>
                                <span x-show="showDetails">Hide file details</span>
                            </button>

                            <div x-show="showDetails" class="mt-3 grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">File Size:</span>
                                    <span class="ml-2 text-gray-900">{{ number_format($upload->file_size / 1024 / 1024, 2) }} MB</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Format:</span>
                                    <span class="ml-2 text-gray-900">{{ strtoupper(pathinfo($upload->file_name, PATHINFO_EXTENSION)) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Visibility:</span>
                                    <span class="ml-2 text-gray-900">{{ $upload->is_public ? 'Public' : 'Private' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2 ml-4">
                        <button @click="showApprovalForm = !showApprovalForm" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            Approve
                        </button>
                        <button @click="showRejectForm = !showRejectForm" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                            Reject
                        </button>
                    </div>
                </div>

                <!-- Approval Form -->
                <div x-show="showApprovalForm" class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                    <form method="POST" action="{{ route('approvals.approve', $upload) }}">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Approval Comment (Optional)
                        </label>
                        <textarea name="comment" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Add any comments for the uploader..."></textarea>
                        <div class="mt-3 flex justify-end space-x-2">
                            <button type="button" @click="showApprovalForm = false" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Confirm Approval
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Rejection Form -->
                <div x-show="showRejectForm" class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                    <form method="POST" action="{{ route('approvals.reject', $upload) }}">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comment" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                        <div class="mt-3 flex justify-end space-x-2">
                            <button type="button" @click="showRejectForm = false" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No pending approvals</h3>
                <p class="mt-1 text-sm text-gray-500">All uploads have been reviewed.</p>
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
@endsection