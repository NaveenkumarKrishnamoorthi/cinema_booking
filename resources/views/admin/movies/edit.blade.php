@extends('layouts.app')

@section('title', 'Edit Movie')

@section('content')
    <h2>Edit Movie</h2>

    <form action="{{ route('admin.movies.update', $movie) }}" method="POST"
          class="bg-white p-4 rounded shadow-sm">
        @csrf @method('PUT')
        @include('admin.movies._form', ['movie' => $movie, 'organizers' => $organizers])

        <button class="btn btn-primary">Update Movie</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection