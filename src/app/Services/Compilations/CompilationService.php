<?php
/**
 * File: CompilationService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Services\Compilations;

use Carbon\Carbon;
use App\Models\User;
use App\Entity\Content;
use App\Entity\ContentStatistic;
use App\Models\Compilations\Compilation;
use App\Repositories\Users\UserRepository;
use App\Repositories\Compilations\VideoRepository;
use App\Repositories\Compilations\CompilationRepository;

/**
 * Class CompilationService
 * @package App\Services\Compilations
 */
class CompilationService
{
    /**
     * The amount of video to compilation
     */
    const COMPILATION_VIDEO_AMOUNT = 10;

    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var VideoRepository
     */
    protected $videoRepository;
    /**
     * @var CompilationRepository
     */
    protected $compilationRepository;
    /**
     * Caching tokens (nextPageToken) for many tags
     *
     * @var array
     */
    protected $tokenCache = [];

    /**
     * CompilationService constructor.
     *
     * @param UserRepository $userRepository
     * @param VideoRepository $videoRepository
     * @param CompilationRepository $compilationRepository
     */
    public function __construct(
        UserRepository $userRepository,
        VideoRepository $videoRepository,
        CompilationRepository $compilationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->videoRepository = $videoRepository;
        $this->compilationRepository = $compilationRepository;
    }

    /**
     * Create a video compilation for user
     *
     * @param int $userId
     * @param array $tags
     * @return bool
     * @throws \Exception
     */
    public function compilation(int $userId, array $tags): bool
    {
        echo Carbon::now()->toDateTimeString() . PHP_EOL;
        /** @var User $user */
        $user = $this->userRepository->find($userId);
        /** @var Content[] $uniqueContents */
        $uniqueContents = [];

        do {
            $uniqueContents = array_merge($this->uniqueContentsForUser(
                $user,
                $this->loadVideoContents($tags)
            ), $uniqueContents);
        } while (count($uniqueContents) < static::COMPILATION_VIDEO_AMOUNT);

        $contents = $this->getPartitionContents($uniqueContents, static::COMPILATION_VIDEO_AMOUNT);

        /** @var Compilation $compilation */
        $compilation = $this->compilationRepository->create([
            'user_id' => $user->getKey(),
        ]);

        $this->videoRepository->storeContents($compilation->getKey(), $contents);
        echo Carbon::now()->toDateTimeString() . PHP_EOL;

        return true;
    }

    /**
     * @param array $tags
     * @return Content[]|array
     * @throws \Exception
     */
    protected function loadVideoContents(array $tags): array
    {
        $contents = [];

        foreach ($tags as $tag) {
            $videos = $this->searchVideos(
                $tag,
                $this->getDateWithMonthAgo()
            );

            if (isset($videos['results'])) {
                $contents = array_merge($this->loadContentsInformation($videos['results']), $contents);
            }
        }

        return $contents;
    }

    /**
     * @param $videos
     * @return Content[]|array
     * @throws \Exception
     */
    protected function loadContentsInformation(&$videos)
    {
        /** @var Content[] $contents */
        $contents = [];

        foreach ($videos as $video) {
            try {
                /** @var Content $content */
                $content = Content::parse($video);

                $videoInfo = \Youtube::getVideoInfo($content->contentId);

                $content->statistic = ContentStatistic::parse(
                    $videoInfo
                );

                $content->title = $videoInfo->snippet->title;
                $content->description = $videoInfo->snippet->description;
                $content->images = $videoInfo->snippet->thumbnails;

                $contents[] = $content;
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
                continue;
            }
        }

        return $contents;
    }

    /**
     * @param User $user
     * @param Content[]|array $contents
     * @return array
     */
    protected function uniqueContentsForUser(User $user, array $contents): array
    {
        $contents = $this->arrayIndex($contents, 'contentId');

        $videos = $this->videoRepository->getVideosAvailable(
            $user,
            array_keys($contents)
        );

        foreach ($videos as $video) {
            if (key_exists($video->content_id, $contents)) {
                unset($contents[$video->content_id]);
            }
        }

        return $contents;
    }

    /**
     * @param Content[]|array $contents
     * @return array
     */
    protected function selectionContents(array $contents): array
    {
        $prepareContents = [];

        foreach ($contents as $content) {
            $prepareContents[$content->statistic->likes] = $content;
        }

        ksort($prepareContents);

        return $prepareContents;
    }

    /**
     * @param array $contents
     * @param int $count
     * @return Content[]|array
     */
    protected function getPartitionContents(array $contents, int $count = self::COMPILATION_VIDEO_AMOUNT): array
    {
        return array_slice(
            $this->selectionContents($contents),
            0,
            $count
        );
    }

    /**
     * @return Carbon
     */
    protected function getDateWithMonthAgo(): Carbon
    {
        $date = Carbon::now();
        $date->month($date->month - 1);
        return $date;
    }

    /**
     * Search only videos
     *
     * @param  string $q Query
     * @param  integer $maxResults number of results to return
     * @param  Carbon|null $publishedBefore
     * @param  string $order Order by
     * @param  array $part
     * @return array API results
     * @throws \Exception
     */
    protected function searchVideos($q, $publishedBefore = null, $maxResults = 30, $order = null, $part = ['id'])
    {
        $params = [
            'q' => $q,
            'type' => 'video',
            'part' => implode(', ', $part),
            'maxResults' => $maxResults,
        ];

        if (!empty($order)) {
            $params['order'] = $order;
        }

        if ($publishedBefore) {
            // RFC3339 - Rules from YouTube API Docs
            $params['publishedBefore'] = $publishedBefore->toRfc3339String();
        }

        //TODO: add relevanceLanguage in user panel
        $params['relevanceLanguage'] = 'ru';

        $paginateResults = \Youtube::paginateResults($params, $this->tokenCache[$q] ?? null);

        if (isset($paginateResults['info']) && ['nextPageToken']) {
            $this->tokenCache[$q] = $paginateResults['info']['nextPageToken'];
        }

        return $paginateResults;
    }

    /**
     * Tools method for index array by value
     *
     * @param $arr
     * @param string $keyName
     * @return array
     */
    protected function arrayIndex($arr, string $keyName)
    {
        $result = [];

        foreach ($arr as $value) {
            $result[$value->{$keyName}] = $value;
        }

        return $result;
    }
}
