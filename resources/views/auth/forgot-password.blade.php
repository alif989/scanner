<x-guest-layout>
    <h4 class="text-center mb-4">
        <i class="bi bi-key me-2"></i>Forgot Password
    </h4>

    <p class="text-muted mb-4">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope me-1"></i>Email Password Reset Link
            </button>
        </div>

        <hr class="my-3">

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
