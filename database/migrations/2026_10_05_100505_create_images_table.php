<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Uploaded photographs live in the database rather than on disk.
 *
 * The container's filesystem is per-instance and thrown away, so a file
 * written during an upload would be invisible to every other instance and
 * gone at the next cold start. The managed database is the only durable,
 * shared place the application already has, and a catalogue of this size
 * measures in megabytes. They are served by a route that sets a long cache
 * lifetime, so the edge serves them after the first request rather than
 * fetching the bytes through PHP every time.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('mime', 60);
            $table->unsignedInteger('bytes');
            // Base64 in a text column rather than bytea: PDO hands bytea back
            // as a stream on Postgres and as a string on SQLite, and the
            // difference is not worth carrying for a few megabytes.
            $table->longText('contents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
