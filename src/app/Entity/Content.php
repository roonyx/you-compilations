<?php
/**
 * File: Content.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-07
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Content - YouTube ValueObject
 * @package App\Entity
 */
class Content
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * Video Id on YouTube
     *
     * @var string
     */
    public $contentId;
    /**
     * @var string
     */
    public $kind;
    /**
     * @var string
     */
    public $etag;
    /**
     *
     * The array has the keys:
     *
     *  - medium     (320x180)
     *  - high       (480x360)
     *  - standard   (640x480)
     *  - maxres     (1280720)
     *
     * @var array
     */
    public $images = [];
    /**
     * @var ContentStatistic
     */
    public $statistic;

    /**
     * Content constructor.
     *
     * @param string $contentId
     */
    public function __construct(string $contentId)
    {
        $this->contentId = $contentId;
    }

    /**
     * @param \stdClass $object
     * @return Content
     * @throws \Exception
     */
    public static function parse(\stdClass $object): self
    {
        if (static::validateFields($object)) {
            $class = new self($object->id->videoId);

            $class->etag = $object->etag;
            $class->kind = $object->kind;

            return $class;
        }

        throw new \Exception('Error when parse object from YouTube API.');
    }

    /**
     * @param \stdClass $object
     * @return bool
     */
    protected static function validateFields(\stdClass $object): bool
    {
        return \property_exists($object, 'id')
            && \property_exists($object->id, 'videoId')
            && \property_exists($object, 'kind');
    }
}
