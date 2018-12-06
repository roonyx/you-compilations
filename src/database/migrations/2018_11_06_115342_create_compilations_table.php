<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCompilationsTable
 */
class CreateCompilationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('compilations', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table
                ->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');

            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 300);
            $table->text('description');
            $table->json('thumbnails');

            $table->unsignedInteger('compilation_id');
            $table
                ->foreign('compilation_id')
                ->on('compilations')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');

            $table->string('content_id', 15)->comment('Content (Video) id for YouTube.');
        });

        Schema::create('video_tags', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('video_id');

            $table
                ->foreign('tag_id')
                ->on('tags')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');

            $table
                ->foreign('video_id')
                ->on('videos')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('video_tags');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('compilations');
    }
}
