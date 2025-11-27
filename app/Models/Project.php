<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    protected $fillable = ['name','repo_url','default_branch'];
    public function archives() { return $this->hasMany(Archive::class); }
    public function logs() { return $this->hasMany(ArchiveLog::class); }
}