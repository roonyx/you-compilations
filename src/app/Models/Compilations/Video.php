<?php
/**
 * File: Video.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use App\Entity\Enums\VideoSize;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

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
 * @property int $views
 * @property int $likes
 * @property int $dislikes
 * @property int $comments
 * @property string $duration
 * @property string $content_id Video - ID on YouTube
 *
 * @property \Illuminate\Support\Carbon|null $published_at
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
        'title', 'description', 'thumbnails', 'duration',
        'views', 'likes', 'dislikes', 'comments',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'thumbnails' => 'array',
    ];
    /**
     * @var array
     */
    protected $dates = [
        'published_at'
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

    /**
     * @return string
     */
    public function interval()
    {
        $dateCurrent = Carbon::now();
        $interval = $dateCurrent->diffAsCarbonInterval($this->published_at);
        $dateSplit = explode(" ", (string)$interval);

        if (!isset($dateSplit[3])) {
            return (string)$interval;
        }

        $splice = array_splice($dateSplit, 0, 4);
        return implode($splice, " ");
    }

    /**
     * @return int|string
     */
    public function viewsFormatted()
    {
        $number = $this->views;

        $abbrevs = [12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => ""];

        foreach ($abbrevs as $exponent => $abbrev) {
            if ($number >= pow(10, $exponent)) {
                $display_num = $number / pow(10, $exponent);
                $decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return number_format($display_num, $decimals) . $abbrev;
            }
        }

        return $number;
    }

    /**
     * @return string
     */
    public function prettyImage(): string
    {
        $thumbnails = $this->thumbnails;

        $image = $thumbnails[VideoSize::STANDARD]
            ?? $thumbnails[VideoSize::HIGH]
            ?? $thumbnails[VideoSize::MEDIUM]
            ?? [];

        if (empty($image['url'])) {
            dd($thumbnails);
        }

        return $image['url'];
    }
}
