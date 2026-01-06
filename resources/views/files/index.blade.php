<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-folder me-2"></i>Files
            </h4>
            <a href="{{ route('files.create') }}" class="btn btn-primary">
                <i class="bi bi-cloud-upload me-1"></i>Upload File
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'file-deleted')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>File deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card" x-data="fileList()">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">
                    <i class="bi bi-list me-1"></i>All Files
                </span>
                <button
                    type="button"
                    class="btn btn-success"
                    x-on:click="generateQR"
                    x-bind:disabled="selectedFiles.length === 0"
                >
                    <i class="bi bi-qr-code me-1"></i>Generate QR Code
                    <span class="badge bg-light text-dark ms-1" x-text="selectedFiles.length">0</span>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        x-on:change="toggleAll($event.target.checked)"
                                        id="selectAll"
                                    >
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Original File</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="{{ $file->id }}"
                                            x-on:change="toggleFile({{ $file->id }})"
                                            x-bind:checked="selectedFiles.includes({{ $file->id }})"
                                        >
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('files.show', $file) }}" class="text-decoration-none fw-medium">
                                        <i class="bi bi-file-earmark me-1"></i>{{ $file->name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $file->original_filename }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $file->formatted_size }}</span>
                                </td>
                                <td class="text-muted">
                                    <small>{{ $file->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('files.show', $file) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-folder2-open display-4 d-block mb-3"></i>
                                        <p class="mb-0">No files uploaded yet.</p>
                                        <a href="{{ route('files.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-cloud-upload me-1"></i>Upload Your First File
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($files->hasPages())
            <div class="card-footer">
                {{ $files->links() }}
            </div>
        @endif

        <!-- QR Code Modal -->
        <div
            class="modal fade"
            id="qrModal"
            tabindex="-1"
            aria-labelledby="qrModalLabel"
            aria-hidden="true"
            x-ref="qrModalEl"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModalLabel">
                            <i class="bi bi-qr-code me-2"></i>QR Code Generated
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="qr-code-container bg-light p-4 rounded mb-4">
                            <canvas x-ref="qrCanvas" class="d-none"></canvas>
                            <img x-ref="qrImage" x-bind:src="'data:image/svg+xml;base64,' + qrCodeData" alt="QR Code" class="img-fluid" style="max-width: 250px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Share URL</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    x-bind:value="shareUrl"
                                    readonly
                                >
                                <button class="btn btn-outline-secondary" type="button" x-on:click="copyUrl">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <small class="text-muted">Share this link or scan the QR code to access files</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" x-on:click="downloadQR">
                            <i class="bi bi-download me-1"></i>Download QR
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function fileList() {
            return {
                selectedFiles: [],
                qrCodeData: '',
                shareUrl: '',
                qrModal: null,
                allFileIds: @json($files->pluck('id')->toArray()),

                init() {
                    this.qrModal = new bootstrap.Modal(this.$refs.qrModalEl);
                },

                toggleFile(id) {
                    const index = this.selectedFiles.indexOf(id);
                    if (index === -1) {
                        this.selectedFiles.push(id);
                    } else {
                        this.selectedFiles.splice(index, 1);
                    }
                },

                toggleAll(checked) {
                    if (checked) {
                        this.selectedFiles = [...this.allFileIds];
                    } else {
                        this.selectedFiles = [];
                    }
                },

                async generateQR() {
                    if (this.selectedFiles.length === 0) return;

                    try {
                        const response = await fetch('{{ route("files.generate-qr") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ file_ids: this.selectedFiles })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.qrCodeData = data.qr_code;
                            this.shareUrl = data.share_url;
                            this.qrModal.show();
                        }
                    } catch (error) {
                        console.error('Error generating QR code:', error);
                    }
                },

                copyUrl() {
                    navigator.clipboard.writeText(this.shareUrl);
                },

                downloadQR() {
                    const canvas = this.$refs.qrCanvas;
                    const ctx = canvas.getContext('2d');
                    const img = new Image();

                    img.onload = () => {
                        canvas.width = 300;
                        canvas.height = 300;
                        ctx.fillStyle = 'white';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, 300, 300);

                        const link = document.createElement('a');
                        link.download = 'qr-code.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                    };

                    img.src = 'data:image/svg+xml;base64,' + this.qrCodeData;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
