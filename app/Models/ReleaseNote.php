<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model {
    protected $fillable = ['archive_id','version','release_date','platforms','notes'];
    protected $casts = ['platforms'=>'array','release_date'=>'date'];
    public function archive() { return $this->belongsTo(Archive::class); }
}
