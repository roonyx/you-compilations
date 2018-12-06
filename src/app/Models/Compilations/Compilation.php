<?php
/**
 * File: Compilation.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use App\Models\User;
use App\Entity\Enums\VideoSize;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Compilations\Compilation
 *
 * @property string $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereUpdatedAt($value)
 *
 * @property Video[] $videos
 *
 * @mixin \Eloquent
 */
class Compilation extends Model
{
    /**
     * Table name
     */
    public const TABLE = 'compilations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'compilation_id', 'id');
    }

    /**
     * @param Compilation $compilation
     * @return array
     */
    public static function prettyImage(Compilation $compilation): array
    {
        /** @var Video[]|Collection $videos */
        $videos = $compilation->videos;

        if (empty($videos)) {
            return [];
        }

        $collections =  $videos->toArray();
        $video = \array_shift($collections);

        return $video['thumbnails'][VideoSize::HIGH]
            ?? $video['thumbnails'][VideoSize::MAXRES]
            ?? $video['thumbnails'][VideoSize::MEDIUM]
            ?? $video['thumbnails'][VideoSize::STANDARD]
            ?? [];
    }
}