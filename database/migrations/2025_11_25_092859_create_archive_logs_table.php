<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveLogsTable extends Migration {
    public function up() {
        Schema::create('archive_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->text('message')->nullable();
            $table->string('commit_hash')->nullable();
            $table->timestamps();
            $table->index('action');
        });
    }
    public function down() { Schema::dropIfExists('archive_logs'); }
}
