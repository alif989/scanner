<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-file-earmark me-2"></i>File Details
            </h4>
            <a href="{{ route('files.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'file-uploaded')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>File uploaded successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('status') === 'file-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>File updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2"></i>File Information
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">File Name</label>
                            <p class="fw-medium mb-0">{{ $file->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Original Filename</label>
                            <p class="mb-0">{{ $file->original_filename }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">File Size</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">{{ $file->formatted_size }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">File Type</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $file->mime_type }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Uploaded By</label>
                            <p class="mb-0">
                                <i class="bi bi-person me-1"></i>{{ $file->uploader?->name ?? 'Unknown' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Uploaded At</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar me-1"></i>{{ $file->created_at->format('F j, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Actions
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('files.download', $file) }}" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i>Download File
                        </a>
                        <a href="{{ route('files.edit', $file) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i>Edit Name
                        </a>
                        <a href="{{ route('files.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-cloud-upload me-1"></i>Upload New File
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('files.destroy', $file) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to delete this file?')">
                                <i class="bi bi-trash me-1"></i>Delete File
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
