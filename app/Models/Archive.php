<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model {
    protected $fillable = ['project_id','version','platform','file_path','file_size','mime_type','commit_hash','released_at'];
    protected $dates = ['released_at'];
    public function project() { return $this->belongsTo(Project::class); }
    public function releaseNote() { return $this->hasOne(related: ReleaseNote::class); }
}