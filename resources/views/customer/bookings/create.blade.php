@extends('layouts.app')

@section('title', 'Book Tickets')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Book Tickets — {{ $movie->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            @if($movie->poster)
                                <img src="{{ $movie->poster }}" class="img-fluid rounded">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <p><strong>Genre:</strong> {{ $movie->genre }}</p>
                            <p><strong>Duration:</strong> {{ $movie->duration }} min</p>
                            <p><strong>Price per ticket:</strong> ₹{{ number_format($movie->price, 2) }}</p>
                            <p><strong>Available seats:</strong> {{ $movie->available_seats }}</p>
                        </div>
                    </div>

                    <form action="{{ route('customer.bookings.store', $movie) }}"
                          method="POST"
                          id="bookingForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Number of Tickets *</label>
                            <input type="number" name="seats" id="seats"
                                   class="form-control @error('seats') is-invalid @enderror"
                                   min="1" max="{{ min(10, $movie->available_seats) }}"
                                   value="{{ old('seats', 1) }}" required>
                            @error('seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Max 10 tickets per booking. Available: {{ $movie->available_seats }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Show Time *</label>
                            <input type="datetime-local" name="show_time"
                                   class="form-control @error('show_time') is-invalid @enderror"
                                   value="{{ old('show_time') }}" required>
                            @error('show_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Total Price: ₹<span id="total">{{ number_format($movie->price, 2) }}</span></strong>
                        </div>

                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="spinner"
                                  role="status" aria-hidden="true"></span>
                            <span id="btnText">Confirm Booking</span>
                        </button>
                        <a href="{{ route('customer.movies.show', $movie) }}"
                           class="btn btn-secondary" id="cancelBtn">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live total price preview
        const price = {{ $movie->price }};
        const seatsInput = document.getElementById('seats');
        const totalEl = document.getElementById('total');

        seatsInput.addEventListener('input', () => {
            const n = parseInt(seatsInput.value) || 0;
            totalEl.textContent = (price * n).toFixed(2);
        });

        // Double-click protection
        const form = document.getElementById('bookingForm');
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        const cancelBtn = document.getElementById('cancelBtn');

        let submitted = false;

        form.addEventListener('submit', function (e) {
            // Block a second submit within the same page
            if (submitted) {
                e.preventDefault();
                return false;
            }
            submitted = true;

            // Disable UI immediately
            btn.disabled = true;
            cancelBtn.classList.add('disabled');
            cancelBtn.style.pointerEvents = 'none';
            cancelBtn.setAttribute('aria-disabled', 'true');

            // Show spinner + change label
            spinner.classList.remove('d-none');
            btnText.textContent = 'Processing...';
        });

        // Extra safety: block double-click on the button itself
        btn.addEventListener('dblclick', (e) => e.preventDefault());
    </script>
@endsection