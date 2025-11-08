@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="mt-2 text-gray-600">Stay updated with important announcements and updates</p>
        </div>

        <div class="flex space-x-3">
            @can('create', App\Models\Notification::class)
                <a href="{{ route('notifications.create') }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Send Notification
                </a>
            @endcan
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Mark All as Read
                </button>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg shadow-sm p-4 {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-indigo-500' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            @if(!$notification->read_at)
                                <span class="flex-shrink-0 inline-block w-2 h-2 bg-indigo-600 rounded-full"></span>
                            @endif
                            <h3 class="text-base font-medium text-gray-900">{{ $notification->title }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                         {{ $notification->type === 'upload_approved' ? 'bg-green-100 text-green-800' : '' }}
                                         {{ $notification->type === 'upload_rejected' ? 'bg-red-100 text-red-800' : '' }}
                                         {{ $notification->type === 'announcement' ? 'bg-blue-100 text-blue-800' : '' }}
                                         {{ $notification->type === 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-gray-700">{{ $notification->body }}</p>

                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            @if($notification->read_at)
                                <span>• Read {{ $notification->read_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="ml-4">
                            @csrf
                            <button type="submit" 
                                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Mark as Read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection