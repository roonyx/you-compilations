<?php
/**
 * File: CompilationRepository.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-07
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Repositories\Compilations;

use App\Models\Tag;
use App\Models\Compilations\Video;
use App\Models\Compilations\Compilation;
use App\Repositories\RepositoryInterface;

/**
 * Class CompilationRepository
 * @package App\Repositories\Compilations
 */
class CompilationRepository implements RepositoryInterface
{
    /**
     * @param array $attributes
     * @return Compilation
     * @throws \Exception
     */
    public function create(array $attributes): Compilation
    {
        $user = Compilation::create($attributes);
        return $user;
    }

    /**
     * @param Compilation $tag
     * @param array $attributes
     * @return bool
     */
    public function update($tag, array $attributes): bool
    {
        return (bool)$tag->update($attributes);
    }

    /**
     * @param int $id
     * @return Compilation|null
     */
    public function get(int $id): ?Compilation
    {
        return Compilation::whereKey($id)->first();
    }

    /**
     * @param int $id
     * @return Compilation
     */
    public function find(int $id): Compilation
    {
        return Compilation::findOrFail($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)\DB::table(Compilation::TABLE)
            ->delete($id);
    }

    /**
     * Assign video to collection
     *
     * @param Compilation $compilation
     * @param string $contentId
     * @param Tag[]|array $tags
     * @return bool
     */
    public function assignVideo(Compilation $compilation, string $contentId, array $tags)
    {
        $video = Video::create([
            'content_id' => $contentId,
            'compilation_id' => $compilation->getKey(),
        ]);

        if ($tags = Tag::whereByNames($tags)) {
            $video->tags()->attach($tags);
            return true;
        }

        return false;
    }

    /**
     * @return Compilation
     */
    public function getModel(): Compilation
    {
        return new Compilation();
    }
}
