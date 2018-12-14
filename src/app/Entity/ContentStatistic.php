<?php
/**
 * File: ContentStatistic.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-07
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;

/**
 * Class ContentStatistic
 * @package App\Entity
 */
class ContentStatistic
{
    /**
     * @var integer
     */
    public $likes = 0;
    /**
     * @var integer
     */
    public $dislikes = 0;
    /**
     * @var integer
     */
    public $views = 0;
    /**
     * @var integer
     */
    public $comments = 0;
    /**
     * @var Carbon
     */
    public $publishedAt;

    /**
     * ContentStatistic constructor.
     *
     * @param int $views
     * @param int $likes
     * @param string $publishedAt
     */
    public function __construct(string $publishedAt, int $views = 0, int $likes = 0)
    {
        $this->views = $views;
        $this->likes = $likes;
        $this->publishedAt = Carbon::parse($publishedAt);
    }

    /**
     * @param \stdClass $object
     * @return ContentStatistic
     * @throws \Exception
     */
    public static function parse(\stdClass $object): self
    {
        if (static::validateFields($object)) {
            $statisticParams = $object->statistics;

            $class = new self(
                $object->snippet->publishedAt,
                (int)$statisticParams->viewCount,
                (int)$statisticParams->likeCount
            );

            $class->dislikes = $statisticParams->dislikeCount;
            $class->comments = $statisticParams->commentCount;

            return $class;
        }

        throw new \Exception('Error when parse content statistics object.');
    }

    /**
     * @param \stdClass $object
     * @return bool
     */
    protected static function validateFields(\stdClass $object): bool
    {
        return \property_exists($object, 'snippet')
            && \property_exists($object, 'statistics')
            && \property_exists($object->statistics, 'viewCount')
            && \property_exists($object->statistics, 'likeCount')
            && \property_exists($object->statistics, 'dislikeCount')
            && \property_exists($object->statistics, 'commentCount')
            && \property_exists($object->snippet, 'publishedAt');
    }
}
