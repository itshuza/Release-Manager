@extends('layouts.app')

@section('content')
<h1>Release Notes</h1>

@foreach($notes as $note)
    <div>
        <h3>{{ $note->version }}</h3>
        {!! $note->notes !!}
    </div>
@endforeach

{{ $notes->links() }}
@endsection
