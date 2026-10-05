<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $movie->title ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Genre *</label>
        <input type="text" name="genre" class="form-control"
               value="{{ old('genre', $movie->genre ?? '') }}" required>
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Description *</label>
        <textarea name="description" rows="3" class="form-control" required>{{ old('description', $movie->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Duration (min) *</label>
        <input type="number" name="duration" class="form-control" min="1"
               value="{{ old('duration', $movie->duration ?? '') }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Release Date *</label>
        <input type="date" name="release_date" class="form-control"
               value="{{ old('release_date', optional($movie->release_date ?? null)->format('Y-m-d')) }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Ticket Price (₹) *</label>
        <input type="number" step="0.01" name="price" class="form-control" min="0"
               value="{{ old('price', $movie->price ?? '') }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Total Seats *</label>
        <input type="number" name="total_seats" class="form-control" min="1"
               value="{{ old('total_seats', $movie->total_seats ?? 100) }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            <option value="active"   {{ old('status', $movie->status ?? 'active') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $movie->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Poster URL</label>
        <input type="text" name="poster" class="form-control"
               value="{{ old('poster', $movie->poster ?? '') }}">
    </div>

    {{-- Owner assignment — shown only when $organizers is passed --}}
    @isset($organizers)
        <div class="col-12 mb-3">
            <label class="form-label">Assign to Organizer</label>
            <select name="organizer_id" class="form-select">
                <option value="">— Admin owned (no organizer) —</option>
                @foreach($organizers as $org)
                    <option value="{{ $org->id }}"
                        {{ (string) old('organizer_id', $movie->organizer_id ?? '') === (string) $org->id ? 'selected' : '' }}>
                        {{ $org->name }} ({{ $org->email }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">
                Leave blank for admin-owned movies. Organizers can only manage movies assigned to them.
            </small>
        </div>
    @endisset
</div>