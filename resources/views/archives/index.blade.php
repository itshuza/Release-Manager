@extends('layouts.app')

@section('content')
<h1>Archives</h1>

<!-- Create Archive Button -->
<a href="{{ route('archives.create') }}" style="display:inline-block; margin-bottom:15px; padding:8px 12px; background:#4CAF50; color:white; text-decoration:none; border-radius:4px;">+ Create Archive</a>

<!-- Filter Form -->
<form method="get" class="mb-4">
  <select name="project_id">
    <option value="">All projects</option>
    @foreach($projects as $p)
      <option value="{{ $p->id }}" {{ request('project_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>
    @endforeach
  </select>

  <input type="text" name="version" placeholder="v1.0.0" value="{{ request('version') }}">

  <select name="platform">
    <option value="">All</option>
    <option value="web">web</option>
    <option value="android_phone">android_phone</option>
    <option value="android_tv">android_tv</option>
    <option value="ios">ios</option>
  </select>

  <button type="submit">Filter</button>
</form>

<!-- Archives Table -->
<table>
<thead>
<tr>
  <th>Project</th>
  <th>Version</th>
  <th>Platform</th>
  <th>Commit</th>
  <th>File</th>
  <th>Action</th>
</tr>
</thead>
<tbody>
@foreach($archives as $a)
<tr>
  <td>{{ $a->project->name }}</td>
  <td>{{ $a->version }}</td>
  <td>{{ $a->platform }}</td>
  <td>{{ $a->commit_hash ?? '-' }}</td>
  <td>{{ $a->file_path ? basename($a->file_path) : '-' }}</td>
  <td>
    @if($a->file_path)
      <a href="{{ route('archives.download', $a) }}">Download</a>
    @endif
  </td>
</tr>
@endforeach
</tbody>
</table>

{{ $archives->links() }}
@endsection

