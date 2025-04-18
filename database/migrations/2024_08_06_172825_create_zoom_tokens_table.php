<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zoom_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->text('token_type');

            $table->text('refresh_token');
            $table->integer('expires_in'); // in seconds
            $table->text('scope');
            $table->string('api_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_tokens');
    }
};
