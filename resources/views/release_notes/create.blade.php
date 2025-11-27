@extends('layouts.app')

@section('content')
<h1>Create Release Note</h1>

{{-- Show validation errors --}}
@if ($errors->any())
    <div style="color:red; margin-bottom: 15px;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('release-notes.store') }}" method="post">
    @csrf

    <label>Archive (optional)</label>
    <select name="archive_id">
        <option value="">--none--</option>
        @foreach($archives as $a)
            <option value="{{ $a->id }}">
                {{ $a->project->name }} - {{ $a->version }} - {{ $a->platform }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Version</label>
    <input name="version" placeholder="v1.0.0" required>

    <br><br>

    <label>Release date</label>
    <input type="date" name="release_date">

    <br><br>

    <label>Notes</label>
    <textarea id="summernote" name="notes"></textarea>

    <br>

    <button type="submit">Save</button>
</form>
@endsection


{{-- Load CSS + JS in the proper section --}}
@section('scripts')
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(function() {
            $('#summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endsection
