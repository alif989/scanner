<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </h4>
    </x-slot>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-folder display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Manage Files</h5>
                    <p class="card-text text-muted">Upload, view, and manage your files.</p>
                    <a href="{{ route('files.index') }}" class="btn btn-primary">
                        <i class="bi bi-folder me-1"></i>View Files
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cloud-upload display-4 text-success mb-3"></i>
                    <h5 class="card-title">Upload File</h5>
                    <p class="card-text text-muted">Upload a new file to the system.</p>
                    <a href="{{ route('files.create') }}" class="btn btn-success">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Now
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-qr-code display-4 text-info mb-3"></i>
                    <h5 class="card-title">Generate QR Code</h5>
                    <p class="card-text text-muted">Select files and generate a shareable QR code.</p>
                    <a href="{{ route('files.index') }}" class="btn btn-info text-white">
                        <i class="bi bi-qr-code me-1"></i>Generate QR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-info-circle me-2"></i>Welcome
        </div>
        <div class="card-body">
            <h5 class="card-title">Hello, {{ Auth::user()->name }}!</h5>
            <p class="card-text">You're logged in to the QR Code Scanner application. Use the navigation above or the quick actions to get started.</p>
        </div>
    </div>
</x-app-layout>
