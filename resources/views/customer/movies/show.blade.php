@extends('layouts.app')
@section('title', $movie->title)
@section('content')
    <div class="row">
        <div class="col-md-4">
            @if($movie->poster)
                <img src="{{ $movie->poster }}" class="img-fluid rounded shadow-sm" alt="{{ $movie->title }}">
            @else
                <div class="bg-secondary text-white text-center p-5 rounded">No Poster</div>
            @endif
        </div>
        <div class="col-md-8">
            <h2>{{ $movie->title }}</h2>
            <p class="text-muted">{{ $movie->genre }} • {{ $movie->duration }} minutes</p>
            <p>{{ $movie->description }}</p>
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <strong>Release Date:</strong> {{ $movie->release_date->format('M d, Y') }}
                </li>
                <li class="list-group-item">
                    <strong>Ticket Price:</strong> ${{ number_format($movie->price, 2) }}
                </li>
                <li class="list-group-item">
                    <strong>Available Seats:</strong>
                    {{ $movie->available_seats }} / {{ $movie->total_seats }}
                </li>
            </ul>
            @if($movie->available_seats > 0)
                <a href="{{ route('customer.bookings.create', $movie) }}"
                   class="btn btn-success btn-lg">Book Tickets</a>
            @else
                <button class="btn btn-secondary btn-lg" disabled>Sold Out</button>
            @endif
            <a href="{{ route('customer.movies.index') }}" class="btn btn-outline-secondary btn-lg">Back</a>
        </div>
    </div>
@endsection
