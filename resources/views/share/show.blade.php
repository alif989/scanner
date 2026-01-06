<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Shared Files') }} - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="min-vh-100 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-qr-code-scan display-1 text-primary"></i>
                        </div>
                        <h1 class="h3 fw-bold">Shared Files</h1>
                        <p class="text-muted">The following files have been shared with you</p>
                    </div>

                    <!-- Files Card -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-folder2-open me-2"></i>
                            {{ $share->files->count() }} {{ Str::plural('File', $share->files->count()) }} Available
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($share->files as $file)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3">
                                                @php
                                                    $iconClass = match(true) {
                                                        str_contains($file->mime_type, 'image') => 'bi-file-image text-success',
                                                        str_contains($file->mime_type, 'pdf') => 'bi-file-pdf text-danger',
                                                        str_contains($file->mime_type, 'word') || str_contains($file->mime_type, 'document') => 'bi-file-word text-primary',
                                                        str_contains($file->mime_type, 'excel') || str_contains($file->mime_type, 'spreadsheet') => 'bi-file-excel text-success',
                                                        str_contains($file->mime_type, 'zip') || str_contains($file->mime_type, 'archive') => 'bi-file-zip text-warning',
                                                        default => 'bi-file-earmark text-secondary'
                                                    };
                                                @endphp
                                                <i class="bi {{ $iconClass }} fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-medium">{{ $file->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $file->original_filename }}
                                                    <span class="badge bg-secondary ms-1">{{ $file->formatted_size }}</span>
                                                </small>
                                            </div>
                                        </div>
                                        <a
                                            href="{{ route('share.download', [$share->token, $file]) }}"
                                            class="btn btn-primary"
                                        >
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($share->expires_at)
                            <div class="card-footer text-muted text-center">
                                <i class="bi bi-clock me-1"></i>
                                This share link expires on {{ $share->expires_at->format('F j, Y g:i A') }}
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 text-muted">
                        <small>
                            <i class="bi bi-shield-check me-1"></i>
                            Powered by {{ config('app.name', 'Laravel') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
