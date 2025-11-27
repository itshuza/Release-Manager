@extends('layouts.app')
@section('content')
<h1>Upload Archive</h1>
<form action="{{ route('archives.store') }}" method="post" enctype="multipart/form-data">
  @csrf
  <label>Project</label>
  <select name="project_id">@foreach($projects as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
  <label>Version</label>
  <input name="version" placeholder="v1.0.0">
  <label>Platform</label>
  <select name="platform">@foreach($platforms as $pl)<option value="{{ $pl }}">{{ $pl }}</option>@endforeach</select>
  <label>File</label>
  <input type="file" name="file">
  <button type="submit">Upload</button>
</form>
@endsection
