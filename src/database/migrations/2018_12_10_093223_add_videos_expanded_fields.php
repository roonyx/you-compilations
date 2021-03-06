<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Services\Compilations\AuthorService;

/**
 * Class AddVideosExpandedFields
 */
class AddVideosExpandedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('thumbnails');
            $table->unsignedInteger('subscribers')->default(0);
            $table->string('channel_id', 30)->unique();

            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('dislikes')->default(0);
            $table->unsignedInteger('comments')->default(0);
            $table->string('duration', 30)->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table
                ->foreign('author_id')
                ->on('authors')
                ->references('id')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->timestamp('published_at', 0)->nullable();
        });

        if (!AuthorService::parse()) {
            throw new \Exception("Error when parse video authors.");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn([
                'author_id',
                'views',
                'likes',
                'dislikes',
                'comments',
                'duration',
            ]);
        });

        Schema::dropIfExists('authors');
    }
}
