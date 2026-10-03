@'
@extends('layouts.app')

@section('title', 'Movies')

@section('content')
    <h2>Now Showing</h2>

    <div class="row mt-3">
        @forelse($movies as $movie)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($movie->poster)
                        <img src="{{ $movie->poster }}" class="card-img-top"
                             style="height:260px;object-fit:cover;" alt="{{ $movie->title }}">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                             style="height:260px;">
                            No Poster
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $movie->title }}</h6>
                        <p class="small text-muted mb-2">
                            {{ $movie->genre }} • {{ $movie->duration }} min
                        </p>
                        <p class="mb-1"><strong>${{ number_format($movie->price, 2) }}</strong></p>
                        <p class="small mb-2">Seats left: {{ $movie->available_seats }}</p>

                        <div class="mt-auto">
                            <a href="{{ route('customer.movies.show', $movie) }}"
                               class="btn btn-sm btn-outline-primary">Details</a>

                            @if($movie->available_seats > 0)
                                <a href="{{ route('customer.bookings.create', $movie) }}"
                                   class="btn btn-sm btn-success">Book</a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>Sold Out</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No movies available right now. Please check back later.
                </div>
            </div>
        @endforelse
    </div>

    {{ $movies->links() }}
@endsection
