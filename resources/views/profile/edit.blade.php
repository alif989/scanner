<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">
            <i class="bi bi-person-circle me-2"></i>Profile
        </h4>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <!-- Update Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person me-2"></i>Profile Information
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Update your account's profile information and email address.</p>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save
                        </button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success ms-2">
                                <i class="bi bi-check-circle me-1"></i>Saved.
                            </span>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-key me-2"></i>Update Password
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Ensure your account is using a long, random password to stay secure.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input
                                type="password"
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                id="current_password"
                                name="current_password"
                                autocomplete="current-password"
                            >
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input
                                type="password"
                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                            >
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                            >
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Save
                        </button>

                        @if (session('status') === 'password-updated')
                            <span class="text-success ms-2">
                                <i class="bi bi-check-circle me-1"></i>Saved.
                            </span>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Account
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAccountModalLabel">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>Are you sure?
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>

                        <div class="mb-3">
                            <label for="delete_password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="delete_password"
                                name="password"
                                placeholder="Password"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
