<?php
/**
 * File: Video.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Compilations\Video
 *
 * @property string $id
 * @property string $title
 * @property string $description
 *
 *
 * The array has the keys:
 *
 *  - medium     (320x180)
 *  - high       (480x360)
 *  - standard   (640x480)
 *  - maxres     (1280720)
 *
 * @property array $thumbnails
 * @property int $author_id
 * @property int $compilation_id
 * @property string $content_id Video - ID on YouTube
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Video whereUpdatedAt($value)
 *
 * @property Author $author
 *
 * @mixin \Eloquent
 */
class Video extends Model
{
    /**
     * Table name
     */
    public const TABLE = 'videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'compilation_id', 'content_id',
        'title', 'description',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'thumbnails' => 'array',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return BelongsTo
     */
    public function compilation(): BelongsTo
    {
        return $this->belongsTo(Compilation::class, 'video_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'video_tags', 'video_id', 'id');
    }
}
