<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Archive;
use App\Models\ArchiveLog;
use Symfony\Component\Process\Process;
use ZipArchive;

class CreateProjectArchive extends Command
{
    protected $signature = 'archive:create {project_id} {version} {--branch=}';
    protected $description = 'Clone/pull a repo and create a code zip archive';

    public function handle()
    {
        $project = Project::findOrFail($this->argument('project_id'));
        $version = $this->argument('version');
        $branch = $this->option('branch') ?: $project->default_branch;
        $workDir = storage_path("app/temp_repos/{$project->id}");
        @mkdir($workDir, 0755, true);

        try {
            if (!is_dir($workDir . '/.git')) {
                $this->info("Cloning {$project->repo_url}...");
                $clone = Process::fromShellCommandline("git clone --branch {$branch} --depth=1 {$project->repo_url} {$workDir}");
                $clone->setTimeout(600);
                $clone->run();

                if (!$clone->isSuccessful()) {
                    ArchiveLog::create([
                        'project_id' => $project->id,
                        'action' => 'failed',
                        'message' => $clone->getErrorOutput()
                    ]);
                    $this->error('Git clone failed: ' . $clone->getErrorOutput());
                    return 1;
                }
            } else {
                $this->info('Pulling latest...');
                $p = Process::fromShellCommandline("cd {$workDir} && git fetch --all --prune && git checkout {$branch} || true && git pull origin {$branch}");
                $p->setTimeout(600);
                $p->run();

                if (!$p->isSuccessful()) {
                    ArchiveLog::create([
                        'project_id' => $project->id,
                        'action' => 'failed',
                        'message' => $p->getErrorOutput()
                    ]);
                    $this->error('Git pull failed: ' . $p->getErrorOutput());
                    return 1;
                }
            }

            $h = Process::fromShellCommandline("cd {$workDir} && git rev-parse --short HEAD");
            $h->run();
            $commitHash = trim($h->getOutput() ?: '');

            $relZip = "archives/{$project->name}/{$version}/code.zip";
            $absZip = storage_path('app/' . $relZip);
            @mkdir(dirname($absZip), 0755, true);

            $this->info('Creating zip...');
            $zip = new ZipArchive();
            if ($zip->open($absZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                ArchiveLog::create([
                    'project_id' => $project->id,
                    'action' => 'failed',
                    'message' => 'zip open failed'
                ]);
                $this->error('Failed to create zip file.');
                return 1;
            }

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($workDir, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                if (strpos($filePath, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR) !== false) continue;
                $relative = substr($filePath, strlen($workDir) + 1);
                $zip->addFile($filePath, $relative);
            }

            $zip->close();

            $size = filesize($absZip);
            $mime = mime_content_type($absZip);

            $archive = Archive::create([
                'project_id' => $project->id,
                'version' => $version,
                'platform' => 'code',
                'file_path' => $relZip,
                'file_size' => $size,
                'mime_type' => $mime,
                'commit_hash' => $commitHash,
                'released_at' => now()
            ]);

            ArchiveLog::create([
                'project_id' => $project->id,
                'action' => 'created',
                'message' => 'Archive created',
                'commit_hash' => $commitHash
            ]);

            $this->info("Archive created: {$relZip} commit: {$commitHash}");
            return 0;

        } catch (\Exception $e) {
            ArchiveLog::create([
                'project_id' => $project->id,
                'action' => 'failed',
                'message' => $e->getMessage()
            ]);
            $this->error('Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
