<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    // List archives with optional filters
    public function index(Request $r) {
        $query = Archive::with('project')->orderBy('created_at', 'desc');

        if ($r->filled('project_id')) $query->where('project_id', $r->project_id);
        if ($r->filled('platform'))   $query->where('platform', $r->platform);
        if ($r->filled('version'))    $query->where('version', $r->version);

        $archives = $query->paginate(15);
        $projects = Project::all();

        return view('archives.index', compact('archives', 'projects'));
    }

    // Show form to upload a new archive
    public function create() {
        $projects = Project::all();
        $platforms = ['web', 'android_phone', 'android_tv', 'ios'];
        return view('archives.create', compact('projects', 'platforms'));
    }

    // Store a new archive
    public function store(Request $r) {
        $r->validate([
            'project_id' => 'required|exists:projects,id',
            'version'    => ['required', 'regex:/^v?\d+\.\d+\.\d+$/'],
            'platform'   => 'required|in:web,android_phone,android_tv,ios',
            'file'       => 'required|file|max:512000', // max 500 MB
        ]);

        $project = Project::findOrFail($r->project_id);
        $version = ltrim($r->version, 'v');
        $platform = $r->platform;

        $file = $r->file('file');
        $filename = $platform . '-' . time() . '-' . $file->getClientOriginalName();

        // Store in storage/app/archives/ProjectName/vX.Y.Z/
        $path = $file->storeAs("archives/{$project->name}/v{$version}", $filename);

        $archive = Archive::create([
            'project_id' => $project->id,
            'version'    => "v{$version}",
            'platform'   => $platform,
            'file_path'  => $path,
            'file_size'  => Storage::size($path),
            'mime_type'  => Storage::mimeType($path),
            'released_at'=> now(),
        ]);

        return redirect()->route('archives.index')->with('success', 'Archive uploaded.');
    }

    // Download an archive
    public function download(Archive $archive) {
        // If the file is missing, abort with 404
        
        if (!Storage::exists($archive->file_path)) {
            abort(404, 'File not found.');
        }

        // Force download
        return Storage::download($archive->file_path, basename($archive->file_path));
    }
}
