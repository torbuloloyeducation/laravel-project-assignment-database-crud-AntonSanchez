<x-layout><br>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Manager</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
            .form-section { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .email-list { max-height: 400px; overflow-y: auto; }
        </style>

    </head>

    <body class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold text-primary mb-3">
                            <i class="bi bi-envelope-fill me-3"></i>
                            Email Manager
                        </h1>
                        <p class="lead text-muted">Add, view, and manage up to 5 unique emails</p>
                    </div>

                    <!-- Messages -->
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center animate__animated animate__fadeIn">
                            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning d-flex align-items-center animate__animated animate__fadeIn">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li><i class="bi bi-x-circle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Add Form -->
                    <div class="form-section p-4 mb-5">
                        <h3 class="mb-4"><i class="bi bi-plus-circle text-success"></i> Add New Email</h3>
                        <form method="POST" action="{{ route('emails.store') }}" class="row g-3">
                            @csrf
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                        placeholder="Enter a valid email address" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-plus-lg"></i> Add
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Emails List -->
                    @if(empty($emails))
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted mb-4"></i>
                            <h4 class="text-muted">No emails saved yet</h4>
                            <p class="text-muted">Add your first email above to get started!</p>
                        </div>
                    @else
                        <div class="form-section p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3><i class="bi bi-list-check text-primary"></i> Saved Emails ({{ count($emails) }}/5)</h3>
                                <span class="badge bg-secondary fs-6">{{ count($emails) }}/5</span>
                            </div>
                            <div class="email-list">
                                @foreach($emails as $index => $email)
                                    <div class="email-card card mb-3">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="bi bi-envelope-fill text-primary me-3 fs-3"></i>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $email }}</h6>
                                                    <small class="text-muted">#{{ $index + 1 }}</small>
                                                </div>
                                            </div>
                                            <form method="POST" action="{{ url('/emails/' . $index) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Delete {{ $email }}?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </body>
    </html>
</x-layout>