<?php
/**
 * File: Author.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-12-12
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Author
 * @package App\Entity
 */
class Author extends Entity
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $channel_id;
    /**
     * @var string
     */
    public $subscribersCount;
    /**
     *
     * The array has the keys:
     *
     *  - default (88x88)
     *  - medium  (240x240)
     *  - high    (800x800)
     *
     * @var array
     */
    public $thumbnails = [];
    /**
     * @var string
     */
    public $kind;
    /**
     * @var string
     */
    public $etag;

    /**
     * Content constructor.
     *
     * @param string $channelId
     */
    public function __construct(string $channelId)
    {
        $this->channel_id = $channelId;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return [
            'name' => $this->name,
            'channel_id' => $this->channel_id,
            'subscribers' => (int)$this->subscribersCount,
            'thumbnails' => (string)json_encode($this->thumbnails ?? [])
        ];
    }

    /**
     * @param \stdClass $object
     * @return Author
     * @throws \Exception
     */
    public static function parse(\stdClass $object): self
    {
        if (static::validateFields($object)) {
            $class = new self($object->id);

            $class->etag = $object->etag;
            $class->kind = $object->kind;

            $class->name = $object->snippet->title;
            $class->thumbnails = $object->snippet->thumbnails;

            $class->subscribersCount = static::getValue(
                $object->statistics,
                'subscriberCount',
                0
            );

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
            && \property_exists($object, 'kind')
            && \property_exists($object, 'snippet')
            && \property_exists($object, 'statistics');
    }
}
