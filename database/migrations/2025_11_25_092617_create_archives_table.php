<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesTable extends Migration {
    public function up() {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('platform')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('commit_hash')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            $table->index(['project_id','version']);
        });
    }
    public function down() { Schema::dropIfExists('archives'); }
}