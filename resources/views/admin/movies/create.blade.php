@extends('layouts.app')

@section('title', 'Add Movie')

@section('content')
    <h2>Add Movie</h2>

    <form action="{{ route('admin.movies.store') }}" method="POST"
          class="bg-white p-4 rounded shadow-sm">
        @csrf
        @include('admin.movies._form', ['movie' => null, 'organizers' => $organizers])

        <button class="btn btn-primary">Save Movie</button>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection