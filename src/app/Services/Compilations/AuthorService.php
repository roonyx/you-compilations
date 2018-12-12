<?php
/**
 * File: AuthorService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-12-11
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Services\Compilations;

use Illuminate\Support\Collection;
use App\Models\Compilations\Video;
use App\Models\Compilations\Author;

/**
 * Class AuthorService
 * @package App\Services\Compilations
 */
class AuthorService
{
    /**
     * @var Collection
     */
    protected $items;
    /**
     * @var Collection
     */
    protected $models;
    /**
     * @var string
     */
    protected $uniqueField = 'channel_id';
    /**
     * @var string
     */
    protected $searchField = 'channel_id';

    /**
     * Authors constructor.
     */
    public function __construct()
    {
        $this->items = collect([]);
        $this->loadModels();
    }

    /**
     * @param Collection|Video[] $videos
     * @return bool
     * @throws \Exception
     */
    public static function parse(Collection $videos = null): bool
    {
        $service = app(static::class);

        if (is_null($videos)) {
            $videos = Video::query()
                ->whereNull('author_id')
                ->get();
        }

        $combiner = [];

        foreach ($videos as $video) {
            if ($info = \Youtube::getVideoInfo($video->content_id)) {
                $snippet = $info->snippet;
                $combiner[$video->content_id] = $snippet->channelId;
                $service->add($snippet->channelTitle, $snippet->channelId);
            }
        }

        if ($service->insert()) {
            $service->combineWithVideo($videos, $combiner);
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $channelId
     * @return void
     */
    public function add(string $name, string $channelId): void
    {
        $this->items->push([
            'name' => $name,
            'channel_id' => $channelId,
        ]);
    }

    /**
     * @param bool $unique
     * @return array
     */
    public function all(bool $unique = true): array
    {
        $items = $this->items;

        if ($unique) {
            $items = $this->uniqueItems();
        }

        return $items->toArray();
    }

    /**
     * @param bool $isInDatabase
     * @return Collection
     */
    public function uniqueItems(bool $isInDatabase = true): Collection
    {
        $items = $this->items->unique($this->uniqueField);

        if (empty($items)) {
            return Collection::make([]);
        }

        if ($isInDatabase) {
            $addingChannelsId = $items->pluck($this->searchField);
            $addedChannelsId = $this->models->pluck($this->searchField);
            $addingChannelsId = $addingChannelsId->diffAssoc($addedChannelsId)->all();

            $items = $items->filter(function ($value) use ($addingChannelsId) {
                return \in_array($value[$this->searchField], $addingChannelsId);
            });
        }

        return $items;
    }

    /**
     * @return bool
     */
    public function insert(): bool
    {
        $items = $this->all();

        if (empty($items)) {
            return true;
        }

        $isInsert = \DB::table(Author::TABLE)->insert($this->all());

        if ($isInsert) {
            $this->models = [];
            $this->loadModels();
        }

        return $isInsert;
    }

    /**
     * $combiner:
     * [
     *    'video_id' => 'channel_id'
     * ]
     *
     * @param Collection|Video[] $videos
     * @param array $combiner
     */
    public function combineWithVideo(Collection $videos, array $combiner): void
    {
        foreach ($videos as $video) {
            if (isset($combiner[$video->content_id])) {
                $channelId = $combiner[$video->content_id];
                $key = $this->models->search(function (Author $author) use ($channelId) {
                    return $author->channel_id == $channelId;
                });
                if ($key !== false && isset($this->models[$key])) {
                    $author = $this->models[$key];
                    $video->author()->associate($author);
                    $video->update();
                }
            }
        }
    }

    protected function loadModels()
    {
        if (empty($this->models)) {
            $this->models = Author::all();
        }
    }

    /**
     * @param bool $unique
     * @return array
     */
    protected function searchValues(bool $unique = true): array
    {
        return $this->items
            ->pluck($this->searchField)
            ->toArray();
    }
}
