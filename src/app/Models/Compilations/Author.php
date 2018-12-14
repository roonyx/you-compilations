<?php
/**
 * File: Author.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-12-10
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use App\Entity\Enums\AvatarSize;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Compilations\Author
 *
 * @property string $id
 * @property string $name
 * @property string $channel_id
 * @property array $thumbnails
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Author whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Author whereUpdatedAt($value)
 *
 * @property Video[]|Collection $videos
 *
 * @mixin \Eloquent
 */
class Author extends Model
{
    /**
     * Table name
     */
    const TABLE = 'authors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'channel_id', 'thumbnails'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'thumbnails' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * @return string
     */
    public function channelLink(): string
    {
        return 'https://www.youtube.com/channel/' . $this->channel_id;
    }

    /**
     * @return string
     */
    public function prettyImage(): string
    {
        $thumbnails = $this->thumbnails;

        if (empty($thumbnails[AvatarSize::DEFAULT]) || empty($thumbnails[AvatarSize::DEFAULT]['url'])) {
            return '';
        }

        return $thumbnails[AvatarSize::DEFAULT]['url'];
    }
}
