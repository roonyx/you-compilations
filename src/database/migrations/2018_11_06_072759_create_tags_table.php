<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTableTags
 */
class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique();
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create('user_tags', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('user_id');

            $table
                ->foreign('tag_id')
                ->on('tags')
                ->references('id')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');

            $table
                ->foreign('user_id')
                ->on('users')
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
        Schema::dropIfExists('user_tags');
        Schema::dropIfExists('tags');
    }
}
