<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ArchiveController extends Controller
{
    // List archives with optional filters
    public function index(Request $request)
    {
        $query = Archive::with('project')->orderBy('created_at', 'desc');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        if ($request->filled('version')) {
            $query->where('version', $request->version);
        }

        $archives = $query->paginate(15);
        $projects = Project::all();

        return view('archives.index', compact('archives', 'projects'));
    }

    // Show form to create a new archive
    public function create()
    {
        $platforms = ['web', 'android_phone', 'android_tv', 'ios'];
        return view('archives.create', compact('platforms'));
    }

    // Store a new archive (file upload or GitHub repo)
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'version'      => ['required', 'regex:/^v?\d+\.\d+\.\d+$/'],
            'platform'     => 'required|in:web,android_phone,android_tv,ios',
            'file'         => 'nullable|file|max:512000', // optional file
            'repo_url'     => 'nullable|url',            // optional GitHub repo
        ]);

        // Create or get project
        $project = Project::firstOrCreate(['name' => $request->project_name]);

        // Clean version and ensure stored format is vX.Y.Z
        $version = ltrim($request->version, 'v');
        $versionLabel = "v{$version}";
        $platform = $request->platform;

        // Prevent duplicates
        if (Archive::where('project_id', $project->id)
                    ->where('version', $versionLabel)
                    ->where('platform', $platform)
                    ->exists()) {
            return back()->withErrors(['duplicate' => 'An archive for this project, version, and platform already exists.']);
        }

        // Use project id in folder to avoid unsafe characters in project name
        $archiveFolder = "archives/{$project->id}/{$project->name}/{$versionLabel}";

        $path = null;

        // Handle GitHub repository URL (download main branch zip)
        if ($request->filled('repo_url')) {
            $repoUrl = rtrim($request->repo_url, '/');

            // Try a few common archive endpoints: prefer `archive/refs/heads/main.zip`, fallback to master
            $zipCandidates = [
                $repoUrl . '/archive/refs/heads/main.zip',
                $repoUrl . '/archive/refs/heads/master.zip',
                $repoUrl . '/archive/main.zip',
                $repoUrl . '/archive/master.zip',
            ];

            $zipContents = null;
            foreach ($zipCandidates as $zipUrl) {
                $resp = Http::withOptions(['verify' => true])->timeout(30)->get($zipUrl);
                if ($resp->ok() && $resp->body()) {
                    $zipContents = $resp->body();
                    break;
                }
            }

            if ($zipContents === null) {
                return back()->withErrors(['repo_url' => 'Failed to download repository ZIP. Ensure the repo URL is correct and the main/master branch exists.']);
            }

            $filename = $platform . '-' . time() . '-' . basename(parse_url($repoUrl, PHP_URL_PATH)) . '.zip';
            $path = "{$archiveFolder}/{$filename}";

            // store the binary contents
            Storage::put($path, $zipContents);

        } elseif ($request->hasFile('file')) {
            // Handle file upload
            $file = $request->file('file');
            $filename = $platform . '-' . time() . '-' . $file->getClientOriginalName();
            $path = $file->storeAs($archiveFolder, $filename);
        } else {
            return back()->withErrors(['file' => 'Please provide a file or a GitHub repository URL.']);
        }

        // Extra safety: ensure file was stored
        if (!$path || !Storage::exists($path)) {
            return back()->withErrors(['file' => 'Failed to save archive file.']);
        }

        // Create archive record
        Archive::create([
            'project_id' => $project->id,
            'version'    => $versionLabel,
            'platform'   => $platform,
            'file_path'  => $path,
            'file_size'  => Storage::size($path),
            'mime_type'  => Storage::mimeType($path),
            'released_at'=> now(),
        ]);

        return redirect()->route('archives.index')->with('success', 'Project archived successfully.');
    }

    // Download an archive
    public function download(Archive $archive)
    {
        if (!Storage::exists($archive->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($archive->file_path, basename($archive->file_path));
    }
}
