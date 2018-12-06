<?php
/**
 * File: VideoRepository.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-07
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Repositories\Compilations;

use App\Models\User;
use App\Entity\Content;
use App\Models\Compilations\Video;
use App\Models\Compilations\Compilation;
use App\Repositories\RepositoryInterface;

/**
 * Class VideoRepository
 * @package App\Repositories\Compilations
 */
class VideoRepository implements RepositoryInterface
{
    /**
     * @param array $attributes
     * @return Video
     * @throws \Exception
     */
    public function create(array $attributes): Video
    {
        $video = Video::create($attributes);
        return $video;
    }


    /**
     * @param Video $tag
     * @param array $attributes
     */
    public function update($tag, array $attributes): void
    {
        $tag->update($attributes);
    }

    /**
     * @param int $id
     * @return Video|null
     */
    public function get(int $id): ?Video
    {
        return Video::whereKey($id)->first();
    }

    /**
     * @param User $user
     * @param string[]|array $contentIds
     * @return Video[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getVideosAvailable($user, $contentIds)
    {
        return Video::query()
            ->leftJoin(Compilation::TABLE, 'compilations.id', '=', 'videos.compilation_id')
            ->where('compilations.user_id', '=', $user->getKey())
            ->whereIn('videos.content_id', $contentIds)
            ->get();
    }

    /**
     * @param int $id
     * @return Video
     */
    public function find(int $id): Video
    {
        return Video::findOrFail($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)\DB::table(Video::TABLE)
            ->delete($id);
    }

    /**
     * @return Video
     */
    public function getModel(): Video
    {
        return new Video();
    }

    /**
     * Save content with converting content to video and saving in DB
     *
     * @param int $compilationId
     * @param Content[]|array $contents
     * @return bool
     */
    public function storeContents(int $compilationId, array $contents): bool
    {
        $videos = [];

        foreach ($contents as $content) {
            $videos[] = [
                'title' => $content->title,
                'description' => $content->description,
                'thumbnails' => (string)json_encode($content->images ?? []),
                'content_id' => $content->contentId,
                'compilation_id' => $compilationId,
            ];
        }

        return \DB::table(Video::TABLE)
            ->insert($videos);
    }
}
