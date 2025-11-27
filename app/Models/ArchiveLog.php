<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ArchiveLog extends Model {
    protected $fillable = ['project_id','action','message','commit_hash'];
    public function project() { return $this->belongsTo(Project::class); }
}
