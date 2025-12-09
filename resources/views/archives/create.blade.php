<form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- New project name -->
    <input type="text" name="project_name" placeholder="Enter new project name" required>

    <input type="text" name="version" placeholder="v1.0.0" required>

    <select name="platform">
        @foreach ($platforms as $platform)
            <option value="{{ $platform }}">{{ $platform }}</option>
        @endforeach
    </select>

    <!-- File upload option -->
    <input type="file" name="file">

    <!-- OR GitHub URL option -->
    <input type="url" name="repo_url" placeholder="https://github.com/user/repo">

    <button type="submit">Archive Repository</button>
</form>
