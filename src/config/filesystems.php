'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'visibility' => 'private',
],
'max_upload_size' => env('UPLOAD_MAX_SIZE', 52428800), // 50MB in bytes
'allowed_types' => env('ALLOWED_FILE_TYPES', 'pdf,png,jpg,jpeg,docx'),