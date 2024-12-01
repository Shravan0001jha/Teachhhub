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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id');
            $table->string('uuid');
            $table->string('zoom_id');
            $table->string('host_id');
            $table->string('topic');
            $table->string('status');
            $table->string('start_time');
            $table->string('duration');
            $table->text('start_url');
            $table->text('join_url');
            $table->string('password');
            $table->integer('batch_id');
            $table->text('response');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
