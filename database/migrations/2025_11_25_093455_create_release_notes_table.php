<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseNotesTable extends Migration {
    public function up() {
        Schema::create('release_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->nullable()->constrained('archives')->nullOnDelete();
            $table->string('version');
            $table->date('release_date')->nullable();
            $table->json('platforms')->nullable();
            $table->text('notes'); // store HTML from WYSIWYG
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('release_notes'); }
}
