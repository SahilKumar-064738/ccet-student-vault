@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Upload New Resource</h1>
        <p class="mt-2 text-gray-600">Share your study materials with fellow students</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('uploads.store') }}" enctype="multipart/form-data" 
              x-data="uploadForm()" @submit="uploading = true">
            @csrf

            <!-- File Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition"
                     @dragover.prevent="dragover = true"
                     @dragleave.prevent="dragover = false"
                     @drop.prevent="handleDrop($event)"
                     :class="{'border-indigo-500 bg-indigo-50': dragover}">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a file</span>
                                <input id="file" name="file" type="file" class="sr-only" required
                                       @change="handleFileSelect($event)"
                                       accept=".pdf,.png,.jpg,.jpeg,.docx">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, PNG, JPG, DOCX up to 50MB</p>
                        
                        <!-- File Preview -->
                        <div x-show="fileName" class="mt-4 p-3 bg-indigo-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="clearFile()" class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Type -->
            <div class="mb-6">
                <label for="upload_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Type <span class="text-red-500">*</span>
                </label>
                <select id="upload_type" name="upload_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('upload_type') border-red-500 @enderror">
                    <option value="">Select Type</option>
                    <option value="question_paper" {{ old('upload_type') == 'question_paper' ? 'selected' : '' }}>Question Paper</option>
                    <option value="notes" {{ old('upload_type') == 'notes' ? 'selected' : '' }}>Notes</option>
                    <option value="assignment" {{ old('upload_type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                    <option value="mst" {{ old('upload_type') == 'mst' ? 'selected' : '' }}>MST Paper</option>
                    <option value="other" {{ old('upload_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('upload_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Branch and Year -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select id="branch_id" name="branch_id" required @change="loadSubjects()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('branch_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ (old('branch_id', auth()->user()->branch_id) == $branch->id) ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                        Year <span class="text-red-500">*</span>
                    </label>
                    <select id="year" name="year" required @change="loadSubjects()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('year') border-red-500 @enderror">
                        <option value="">Select Year</option>
                        <option value="1" {{ old('year', auth()->user()->year) == 1 ? 'selected' : '' }}>1st Year</option>
                        <option value="2" {{ old('year', auth()->user()->year) == 2 ? 'selected' : '' }}>2nd Year</option>
                        <option value="3" {{ old('year', auth()->user()->year) == 3 ? 'selected' : '' }}>3rd Year</option>
                        <option value="4" {{ old('year', auth()->user()->year) == 4 ? 'selected' : '' }}>4th Year</option>
                    </select>
                    @error('year')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject -->
            <div class="mb-6">
                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <select id="subject_id" name="subject_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teacher Name -->
            <div class="mb-6">
                <label for="teacher_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Teacher Name (Optional)
                </label>
                <input type="text" id="teacher_name" name="teacher_name" value="{{ old('teacher_name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="e.g., Dr. Sharma">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description (Optional)
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                          placeholder="Add any additional information about this resource...">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
            </div>

            <!-- Exam Year -->
            <div class="mb-6">
                <label for="exam_year" class="block text-sm font-medium text-gray-700 mb-2">
                    Exam Year (Optional)
                </label>
                <input type="number" id="exam_year" name="exam_year" value="{{ old('exam_year', date('Y')) }}"
                       min="2000" max="{{ date('Y') + 1 }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                       placeholder="2024">
            </div>

            <!-- Is Public -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="is_public" name="is_public" value="1" checked
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_public" class="ml-2 block text-sm text-gray-700">
                        Make this resource publicly available to all students
                    </label>
                </div>
            </div>

            <!-- Progress Bar -->
            <div x-show="uploading" class="mb-6">
                <div class="bg-gray-200 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" 
                         :style="`width: ${uploadProgress}%`"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2 text-center">
                    Uploading... <span x-text="uploadProgress"></span>%
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('uploads.my-uploads') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        :disabled="uploading"
                        :class="uploading ? 'opacity-50 cursor-not-allowed' : ''"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <span x-show="!uploading">Upload Resource</span>
                    <span x-show="uploading">Uploading...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function uploadForm() {
    return {
        fileName: '',
        fileSize: '',
        dragover: false,
        uploading: false,
        uploadProgress: 0,

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;
                this.fileSize = this.formatFileSize(file.size);
            }
        },

        handleDrop(event) {
            this.dragover = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                document.getElementById('file').files = event.dataTransfer.files;
                this.fileName = file.name;
                this.fileSize = this.formatFileSize(file.size);
            }
        },

        clearFile() {
            document.getElementById('file').value = '';
            this.fileName = '';
            this.fileSize = '';
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        },

        loadSubjects() {
            const branchId = document.getElementById('branch_id').value;
            const year = document.getElementById('year').value;
            
            if (branchId && year) {
                fetch(`/api/subjects?branch_id=${branchId}&year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('subject_id');
                        select.innerHTML = '<option value="">Select Subject</option>';
                        data.forEach(subject => {
                            select.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                        });
                    });
            }
        }
    }
}
</script>
@endsection